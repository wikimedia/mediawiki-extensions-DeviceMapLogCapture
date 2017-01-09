<?php

/*
  Licensed to the Apache Software Foundation (ASF) under one
  or more contributor license agreements.  See the NOTICE file
  distributed with this work for additional information
  regarding copyright ownership.  The ASF licenses this file
  to you under the Apache License, Version 2.0 (the
  "License"); you may not use this file except in compliance
  with the License.  You may obtain a copy of the License at

   http://www.apache.org/licenses/LICENSE-2.0

  Unless required by applicable law or agreed to in writing,
  software distributed under the License is distributed on an
  "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
  KIND, either express or implied.  See the License for the
  specific language governing permissions and limitations
  under the License.
*/

if ( !defined( 'MEDIAWIKI' ) ) {
	die( 'This is not a valid entry point to MediaWiki.' );
}

$wgExtensionCredits['other'][] = array(
	'path' => __FILE__,
	'name' => 'Device Map Log Capture',
	'author' => 'Patrick Reilly',
	'version' => '0.0.1',
	'url' => 'https://www.mediawiki.org/wiki/Extension:DeviceMapLogCapture'
);

$dir = dirname( __FILE__ ) . '/';
$wgAutoloadClasses['DeviceMapLogCaptureHooks'] = $dir . 'DeviceMapLogCapture.hooks.php';
$wgAutoloadClasses['ApiDeviceMapLogCapture'] = $dir . 'ApiDeviceMapLogCapture.php';


$wgHooks['LoadExtensionSchemaUpdates'][] = 'DeviceMapLogCaptureHooks::loadExtensionSchemaUpdates';
$wgAPIModules['devicemaplogcapture'] = 'ApiDeviceMapLogCapture';

$wgMessagesDirs['DeviceMapLogCapture'] = __DIR__ . '/i18n';
