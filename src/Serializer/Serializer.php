<?php

namespace Serializer;

use BaseExceptions\ApiException;
use Doctrine\{
    Common\Persistence\Proxy,
    ORM\PersistentCollection
};
use Serializer\Serializers\JsonSerializer;
use Symfony\Component\Yaml\Yaml;
use Traits\ContainerAwareTrait;

class Serializer
{
    use ContainerAwareTrait;

    const FORMAT_JSON = 'json';
    const FORMAT_XML = 'xml';

    const EXCLUSION_TYPE_ONLY = 'only';
    const EXCLUSION_TYPE_EXCEPT = 'except';

    protected $config;
    protected $format;

    protected $entityConfigs = [];

    protected $objectStack = [];

    public function serialize($data, $config, $format = self::FORMAT_JSON) {
        $this->config = $config;
        $this->format = $format;

        $processed = $this->visit($data);

        switch ($format) {
            case self::FORMAT_JSON:
                $serializer = new JsonSerializer();
                break;
            default:
                throw new ApiException('Serialization format "' . $format . '" is not supported yet.');
        }

        return $serializer->serialize($processed);
    }

    protected function visit($data, $format = null) {
        if (is_array($data) || (is_object($data) && $data instanceof \IteratorAggregate)) {
            // Array or collection
            if ($data instanceof PersistentCollection && !$data->isInitialized()) {
                return [];
            }

            $array = [];
            foreach ($data as $k => $v) {
                if(preg_match('/^\+?\d+$/', $k)) {
                    $array[] = $this->visit($v);
                } else {
                    $array[$k] = $this->visit($v);
                }
            }
            $result = $array;
        } else if (is_object($data)) {
            // Object
            if ($data instanceof \DateTime) {
                // DateTime
                $result = $data->format($format ?: 'Y-m-d');
            } else {
                $hash = spl_object_hash($data);
                if (in_array($hash, $this->objectStack)) {
                    return null;
                }
                array_push($this->objectStack, $hash);
                $ref = new \ReflectionClass($data);
                if ($data instanceof Proxy) {
                    if (!$data->__isInitialized()) {
                        return new \stdClass();
                    }
                    $ref = $ref->getParentClass();
                }

                $class = str_replace('Proxies\\__CG__\\', '', $ref->getName());

                $classConfig = isset($this->config[$class]) ? $this->config[$class] : [];
                $jmsConfig = $this->getEntityConfig($class);

                $resultReady = false;
                if (empty($classConfig) && empty($jmsConfig)) {
                    $methods = $ref->getMethods();
                    foreach ($methods as $method) {
                        if ($method->getName() == '__toString') {
                            $result = $data->__toString();
                            $resultReady = true;
                            break;
                        }
                    }
                }

                if (empty($classConfig)) {
                    $classConfig = [
                        'type' => self::EXCLUSION_TYPE_EXCEPT,
                        'fields' => [],
                    ];
                }

                if (!$resultReady) {
                    $array = [];
                    switch ($classConfig['type']) {
                        case self::EXCLUSION_TYPE_ONLY:
                            foreach ($classConfig['fields'] as $field) {
                                if (is_string($field)) {
                                    $prop = $ref->getProperty($field);
                                    $prop->setAccessible(true);
                                    $array[$field] = $this->visit($prop->getValue($data));
                                } else if (is_array($field)) {
                                    $realField = array_keys($field)[0];
                                    $prop = $ref->getProperty($realField);
                                    $prop->setAccessible(true);
                                    $array[$realField] = $this->visit($prop->getValue($data), isset($field[$realField]['format']) ? $field[$realField]['format'] : null);
                                }
                            }

                            if (isset($classConfig['virtual'])) {
                                foreach ($classConfig['virtual'] as $key => $method) {
                                    if (is_string($method)) {
                                        $array[$key] = $this->visit($data->$method());
                                    } elseif (is_array($method) && isset($method['key']) && isset($method['value'])) {
                                        $index = $data->{$method['key']}();
                                        if (is_null($index)) {
                                            $index = $key;
                                        }
                                        $array[$index] = $this->visit($data->{$method['value']}());
                                    } else {
                                        throw new ApiException('wrong_format', 500);
                                    }
                                }
                            }

                            break;
                        case self::EXCLUSION_TYPE_EXCEPT:
                            if (isset($jmsConfig['fields'])) {
                                foreach ($jmsConfig['fields'] as $field => $name) {
                                    if (!in_array($field, $classConfig['fields'])) {
                                        $prop = $ref->getProperty($field);
                                        $prop->setAccessible(true);
                                        $array[$name] = $this->visit($prop->getValue($data));
                                    }
                                }
                            }

                            if (isset($jmsConfig['virtual'])) {
                                foreach ($jmsConfig['virtual'] as $name => $method) {
                                    if (!in_array($name, $classConfig['fields'])) {
                                        $array[$name] = $data->$method();
                                    }
                                }
                            }

                            break;
                        default:
                            throw new ApiException('Invalid serialization type "' . $class['type'] . '" for class ' . $class);
                    }

                    array_pop($this->objectStack);
                    $result = $array;
                }
            }
        } else {
            // Plain var
            $result = $data;
        }

        return $result;
    }

    protected function getEntityConfig($entity) {
        if (!isset($this->entityConfigs[$entity])) {
            if (preg_match('~(Modules\\\.*Bundle)~is', $entity, $matches)) {
                $bundle = str_replace('\\', '/', $matches[1]);
                preg_match('~([^\\\]+)$~is', $entity, $matches);
                $shortName = $matches[1];
                $configPath = $this->getContainer()->get('kernel')->getRootDir() . '/../src/' . $bundle . '/Resources/config/serializer/Entity.' . $shortName . '.yml';
                $this->entityConfigs[$entity] = $this->parseConfig($configPath, $entity);
            } else {
                $this->entityConfigs[$entity] = [];
            }
        }

        return $this->entityConfigs[$entity];
    }

    protected function parseConfig($configPath, $entity) {
        if (!file_exists($configPath)) {
            return [];
        }

        $raw = Yaml::parse(file_get_contents($configPath));
        if (!(isset($raw[$entity]) && isset($raw[$entity]['properties']))) {
            return [];
        }

        $result = [
            'fields' => [],
            'virtual' => [],
        ];

        foreach ($raw[$entity]['properties'] as $field => $fieldConfig) {
            if ($fieldConfig['expose']) {
                $name = isset($fieldConfig['serialized_name']) ? $fieldConfig['serialized_name'] : $field;
                $result['fields'][$field] = $name;
            }
        }

        if (isset($raw[$entity]['virtual_properties'])) {
            foreach ($raw[$entity]['virtual_properties'] as $method => $fieldConfig) {
                $result['virtual'][$fieldConfig['serialized_name']] = $method;
            }
        }

        return $result;
    }
}