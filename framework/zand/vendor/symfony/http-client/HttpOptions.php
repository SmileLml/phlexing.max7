<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\HttpClient;

use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * A helper providing autocompletion for available options.
 *
 * @see HttpClientInterface for a description of each options.
 *
 * @author Nicolas Grekas <p@tchwork.com>
 */
class HttpOptions
{
    /**
     * @var mixed[]
     */
    private $options = [];

    public function toArray()
    {
        return $this->options;
    }

    /**
     * @return $this
     * @param string $user
     * @param string $password
     */
    public function setAuthBasic($user, $password = '')
    {
        $this->options['auth_basic'] = $user;

        if ('' !== $password) {
            $this->options['auth_basic'] .= ':'.$password;
        }

        return $this;
    }

    /**
     * @return $this
     * @param string $token
     */
    public function setAuthBearer($token)
    {
        $this->options['auth_bearer'] = $token;

        return $this;
    }

    /**
     * @return $this
     * @param mixed[] $query
     */
    public function setQuery($query)
    {
        $this->options['query'] = $query;

        return $this;
    }

    /**
     * @return $this
     * @param mixed[] $headers
     */
    public function setHeaders($headers)
    {
        $this->options['headers'] = $headers;

        return $this;
    }

    /**
     * @param mixed $body
     *
     * @return $this
     */
    public function setBody($body)
    {
        $this->options['body'] = $body;

        return $this;
    }

    /**
     * @return $this
     * @param mixed $json
     */
    public function setJson($json)
    {
        $this->options['json'] = $json;

        return $this;
    }

    /**
     * @return $this
     * @param mixed $data
     */
    public function setUserData($data)
    {
        $this->options['user_data'] = $data;

        return $this;
    }

    /**
     * @return $this
     * @param int $max
     */
    public function setMaxRedirects($max)
    {
        $this->options['max_redirects'] = $max;

        return $this;
    }

    /**
     * @return $this
     * @param string $version
     */
    public function setHttpVersion($version)
    {
        $this->options['http_version'] = $version;

        return $this;
    }

    /**
     * @return $this
     * @param string $uri
     */
    public function setBaseUri($uri)
    {
        $this->options['base_uri'] = $uri;

        return $this;
    }

    /**
     * @return $this
     * @param mixed[] $vars
     */
    public function setVars($vars)
    {
        $this->options['vars'] = $vars;

        return $this;
    }

    /**
     * @return $this
     * @param bool $buffer
     */
    public function buffer($buffer)
    {
        $this->options['buffer'] = $buffer;

        return $this;
    }

    /**
     * @return $this
     * @param callable $callback
     */
    public function setOnProgress($callback)
    {
        $this->options['on_progress'] = $callback;

        return $this;
    }

    /**
     * @return $this
     * @param mixed[] $hostIps
     */
    public function resolve($hostIps)
    {
        $this->options['resolve'] = $hostIps;

        return $this;
    }

    /**
     * @return $this
     * @param string $proxy
     */
    public function setProxy($proxy)
    {
        $this->options['proxy'] = $proxy;

        return $this;
    }

    /**
     * @return $this
     * @param string $noProxy
     */
    public function setNoProxy($noProxy)
    {
        $this->options['no_proxy'] = $noProxy;

        return $this;
    }

    /**
     * @return $this
     * @param float $timeout
     */
    public function setTimeout($timeout)
    {
        $this->options['timeout'] = $timeout;

        return $this;
    }

    /**
     * @return $this
     * @param float $maxDuration
     */
    public function setMaxDuration($maxDuration)
    {
        $this->options['max_duration'] = $maxDuration;

        return $this;
    }

    /**
     * @return $this
     * @param string $bindto
     */
    public function bindTo($bindto)
    {
        $this->options['bindto'] = $bindto;

        return $this;
    }

    /**
     * @return $this
     * @param bool $verify
     */
    public function verifyPeer($verify)
    {
        $this->options['verify_peer'] = $verify;

        return $this;
    }

    /**
     * @return $this
     * @param bool $verify
     */
    public function verifyHost($verify)
    {
        $this->options['verify_host'] = $verify;

        return $this;
    }

    /**
     * @return $this
     * @param string $cafile
     */
    public function setCaFile($cafile)
    {
        $this->options['cafile'] = $cafile;

        return $this;
    }

    /**
     * @return $this
     * @param string $capath
     */
    public function setCaPath($capath)
    {
        $this->options['capath'] = $capath;

        return $this;
    }

    /**
     * @return $this
     * @param string $cert
     */
    public function setLocalCert($cert)
    {
        $this->options['local_cert'] = $cert;

        return $this;
    }

    /**
     * @return $this
     * @param string $pk
     */
    public function setLocalPk($pk)
    {
        $this->options['local_pk'] = $pk;

        return $this;
    }

    /**
     * @return $this
     * @param string $passphrase
     */
    public function setPassphrase($passphrase)
    {
        $this->options['passphrase'] = $passphrase;

        return $this;
    }

    /**
     * @return $this
     * @param string $ciphers
     */
    public function setCiphers($ciphers)
    {
        $this->options['ciphers'] = $ciphers;

        return $this;
    }

    /**
     * @return $this
     * @param string|mixed[] $fingerprint
     */
    public function setPeerFingerprint($fingerprint)
    {
        $this->options['peer_fingerprint'] = $fingerprint;

        return $this;
    }

    /**
     * @return $this
     * @param bool $capture
     */
    public function capturePeerCertChain($capture)
    {
        $this->options['capture_peer_cert_chain'] = $capture;

        return $this;
    }

    /**
     * @return $this
     * @param mixed $value
     * @param string $name
     */
    public function setExtra($name, $value)
    {
        $this->options['extra'][$name] = $value;

        return $this;
    }
}
