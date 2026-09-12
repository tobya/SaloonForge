<?php

  namespace Tobya\SaloonForge\Services;

  class ConfigService
  {

      const string EmptyConfigValue = 'THIS IS A MISSING VALUE';

      public function __construct(

          public string $integration

      )
      {
      }

      private function configPath(): string
      {
           return 'saloonforge.integrations.' . $this->integration ;
      }

      /**
       * Retrieve a config value from the correct integration
       * in the saloonforge.php config file.
       * @param string $string
       * @return mixed
       */
      public function Config(string $string) : mixed
      {

          $config_path = $this->configPath();
          $integration_config_value = config($config_path . '.' . $string,self::EmptyConfigValue);

          // Value does not exist, pull it from the default.
          if ($integration_config_value === self::EmptyConfigValue) {
              $config_path = 'saloonforge.integrations.Default' ;
              $integration_config_value = config($config_path . '.' . $string);
          }

          return $integration_config_value;
      }

      /**
       * Alias for config()
       * @param $string
       * @return mixed
       */
      public function get($string) : mixed
      {
          return $this->Config($string);
      }

      /**
       * Does a section exist in the config file for the integration.
       * @return bool
       */
      public function IntegrationExists() : bool
      {
          $config_integration_array = config($this->configPath());
          return is_array($config_integration_array);
      }


  }
