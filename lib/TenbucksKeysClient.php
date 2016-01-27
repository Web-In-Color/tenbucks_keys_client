<?php

/*
 * The MIT License
 *
 * Copyright 2016 tenbucks.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

/**
 * Send API keys TenBucks API server
 *
 * @author Gary P. <gary@webincolor.fr>
 */
final class TenbucksKeysClient
{

    const URL = 'https://apps.tenbucks.io/';

    /**
     * @var string Key used to sign data 
     */
    private $encryption_key;
    
    /**
     * Retrieve encryption key
     * 
     * @param string $url shop url
     * @return \TenbucksKeysClient
     * @throws Exception
     */
    public function setKey($url)
    {
        $query = $this->call('key_manager/new', array(
            'url' => $url
        ));
        
        if (!array_key_exists('key', $query)) {
            throw new Exception('Can\'t retrieve encryption key.');
        }

        $this->encryption_key = $query['key'];

        return $this;
    }
    
    /**
     * Send API keys
     * @param array $data
     * @return bool operation success
     * 
     */
    public function send(array $data)
    {
        $query = $this->call('key_manager/set', $data);
        return array_key_exists('success', $query) ? (bool)$query['success'] : false;
    }

    private function call($path, array $data = array())
    {
        $url = self::URL.preg_replace('/^\//', '', $path);
        
        $request_headers = array(
            'Accept: application/json',
			'User-Agent: TenbucksKeys API Client'
		);
        
        if (!empty($this->encryption_key)) {
            $request_headers[] = 'X-Tenbucks-Signature: '.$this->getSignature($data);
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        // Process
        $response = curl_exec($ch);
        if ( empty( $response ) ) {
			$response = array(
				'http_code' => curl_getinfo($ch, CURLINFO_HTTP_CODE),
				'error' => curl_error($ch)
			);
		}
        curl_close($ch);

        return is_array($response) ? $response : json_decode($response, true);
    }
    
    private function getSignature(array $data)
    {
        ksort($data);
        return hash_hmac('sha256', http_build_query($data), $this->encryption_key);
    }

}
