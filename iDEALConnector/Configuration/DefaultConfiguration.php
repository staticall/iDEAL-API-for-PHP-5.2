<?php
class iDEALConnector_Configuration_DefaultConfiguration implements iDEALConnector_Configuration_IConnectorConfiguration
{
    private $certificate = '';
    private $privateKey  = '';
    private $passphrase  = '';

    private $acquirerCertificate = '';

    private $merchantID = '';
    private $subID      = 0;
    private $returnURL  = '';

    private $expirationPeriod       = 60;
    private $acquirerDirectoryURL   = '';
    private $acquirerTransactionURL = '';
    private $acquirerStatusURL      = '';
    private $timeout                = 10;

    private $proxy    = null;
    private $proxyUrl = '';

    private $logFile = 'logs/connector.log';
    private $logLevel = iDEALConnector_Log_LogLevel::Error;

    function __construct($path)
    {
        $this->loadFromFile($path);
    }

    private function loadFromFile($path)
    {
        $file = fopen($path,'r');
        $config_data = array();

        if ($file)
        {
            while (!feof($file))
            {
                $buffer = fgets($file);

                /* @var $buffer array() */
                $buffer = trim($buffer);

                if (!empty($buffer))
                {
                    if ($buffer[0] != '#')
                    {
                        $pos = strpos($buffer, '=');

                        if ($pos > 0 && $pos != (strlen($buffer) - 1))
                        {
                            $dumb = trim(substr($buffer, 0, $pos));

                            if (!empty($dumb))
                            {
                                // Populate the configuration array
                                $config_data[strtoupper(substr($buffer, 0, $pos))] = substr($buffer, $pos + 1);
                            }
                        }
                    }
                }
            }
        }

        fclose($file);

        if(!empty($config_data['MERCHANTID']))
        {
            $this->merchantID = $config_data['MERCHANTID'];
        }

        if(!empty($config_data['SUBID']))
        {
            $this->subID = intval($config_data['SUBID']);
        }

        if(!empty($config_data['MERCHANTRETURNURL']))
        {
            $this->returnURL = $config_data['MERCHANTRETURNURL'];
        }


        if(!empty($config_data['ACQUIRERURL']))
        {
            $this->acquirerDirectoryURL   = $config_data['ACQUIRERURL'];
            $this->acquirerStatusURL      = $config_data['ACQUIRERURL'];
            $this->acquirerTransactionURL = $config_data['ACQUIRERURL'];
        }

        if(!empty($config_data['ACQUIRERTIMEOUT']))
        {
            $this->timeout = intval($config_data['ACQUIRERTIMEOUT']);
        }

        if(!empty($config_data['EXPIRATIONPERIOD']))
        {
            $this->expirationPeriod = 60;

            if($config_data['EXPIRATIONPERIOD'] !== 'PT1H')
            {
                $value = substr($config_data['EXPIRATIONPERIOD'], 2, strlen($config_data['EXPIRATIONPERIOD']) - 3);

                if (is_numeric($value))
                {
                    $this->expirationPeriod = (int)$value;
                }
            }
        }

        if(!empty($config_data['CERTIFICATE0']))
        {
            $this->acquirerCertificate = $config_data['CERTIFICATE0'];
        }

        if(!empty($config_data['PRIVATECERT']))
        {
            $this->certificate = $config_data['PRIVATECERT'];
        }

        if(!empty($config_data['PRIVATEKEY']))
        {
            $this->privateKey = $config_data['PRIVATEKEY'];
        }

        if(!empty($config_data['PRIVATEKEYPASS']))
        {
            $this->passphrase = $config_data['PRIVATEKEYPASS'];
        }

        if(!empty($config_data['PROXY']))
        {
            $this->proxy = $config_data['PROXY'];
        }

        if(!empty($config_data['PROXYACQURL']))
        {
            $this->proxyUrl = $config_data['PROXYACQURL'];
        }

        if(!empty($config_data['LOGFILE']))
        {
            $this->logFile = $config_data['LOGFILE'];
        }

        if(!empty($config_data['TRACELEVEL']))
        {
            $level = strtolower($config_data['TRACELEVEL']);

            switch ($level)
            {
                case 'debug':
                        $this->logLevel = iDEALConnector_Log_LogLevel::Debug;
                    break;
                case 'error':
                        $this->logLevel = iDEALConnector_Log_LogLevel::Error;
                    break;
                default:
                    break;
            }
        }
    }

    public function getAcquirerCertificatePath()
    {
        return $this->acquirerCertificate;
    }

    public function getCertificatePath()
    {
        return $this->certificate;
    }

    public function getExpirationPeriod()
    {
        return $this->expirationPeriod;
    }

    public function getMerchantID()
    {
        return $this->merchantID;
    }

    public function getPassphrase()
    {
        return $this->passphrase;
    }

    public function getPrivateKeyPath()
    {
        return $this->privateKey;
    }

    public function getMerchantReturnURL()
    {
        return $this->returnURL;
    }

    public function getSubID()
    {
        return $this->subID;
    }

    public function getAcquirerTimeout()
    {
        return $this->timeout;
    }

    public function getAcquirerDirectoryURL()
    {
        return $this->acquirerDirectoryURL;
    }

    public function getAcquirerStatusURL()
    {
        return $this->acquirerStatusURL;
    }

    public function getAcquirerTransactionURL()
    {
        return $this->acquirerTransactionURL;
    }

    /**
     * @return string
     */
    public function getProxy()
    {
        return $this->proxy;
    }

    /**
     * @return string
     */
    public function getProxyUrl()
    {
        return $this->proxyUrl;
    }

    /**
     * @return string
     */
    public function getLogFile()
    {
        return $this->logFile;
    }

    /**
     * @return integer
     */
    public function getLogLevel()
    {
        return $this->logLevel;
    }
}