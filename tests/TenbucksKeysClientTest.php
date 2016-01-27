<?php

/*
 * The MIT License
 *
 * Copyright 2016 gary.
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
 * Test of TenbucksKeysClient class
 *
 * @author Gary P. <gary@webincolor.fr>
 */
class TenbucksKeysClientTest extends PHPUnit_Framework_TestCase
{

    public function testSetKey()
    {
        // Client
        $client = new TenbucksKeysClient();

        // Assert
        $this->assertInstanceOf('TenbucksKeysClient', $client->setKey('http://example.org'));
    }

    public function testSend()
    {
        // Client
        $client = new TenbucksKeysClient();
        $url = 'http://example.org';
        $data = array(
            'url'         => $url, // complete (with protocol) shop url
            'platform'    => 'TestPlatform', // Prestashop|Magento|WooCommerce
            'credentials' => array(
                'key'    => md5('test_key'), // key
                'secret' => md5('test_secret'), // secret
            )
        );

        // Assert
        $this->assertTrue($client->setKey($url)->send($data));
    }

}
