<?php
/**
 * DeviceMap Log Capture API module
 *
 * @file
 * @ingroup API
 */

class ApiDeviceMapLogCapture extends ApiBase {

	/**
	 * API devicemaplogcapture action
	 *
	 * Parameters:
	 * 		eventid: event name
	 * 		token: unique identifier for a user session
	 *
	 * @see includes/api/ApiBase#execute()
	 */
	public function execute() {
		$params = $this->extractRequestParams();
		$eventId = $params['eventid'];
		$token = $params['token'];
		$site = $params['site'];
		$deviceMap = $params['dmap'];
		$countryCode = null;
		$ip = $this->getRequest()->getVal( 'ip', $this->getRequest()->getIP() );
		if ( IP::isValid( $ip ) ) {
			if ( function_exists( 'geoip_country_code_by_name' ) ) {
				$countryCode = geoip_country_code_by_name( $ip );
			}
		}
		$userAgent = $_SERVER['HTTP_USER_AGENT'];
		DeviceMapLogCaptureHooks::recordDevice( $eventId, $token, $site, $deviceMap, $countryCode, $userAgent );

		$result = $this->getResult();
		$data = array();
		$data['dmap'] = $deviceMap;
		$data['country_code'] = $countryCode;
		$result->addValue( 'query', $this->getModuleName(), $data );
	}

	public function getParamDescription() {
		return array(
			'eventid' => 'string of eventID',
			'token'  => 'unique edit ID for this edit session',
			'site' => 'the site being used',
		);
	}

	public function getDescription() {
		return array(
			'Combined with User-Agent and x-wap-profile HTTP headers, this provides basic device information that the server can save'
		);
	}

	public function getAllowedParams() {
		return array(
			'eventid' => array(
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_REQUIRED => true,
			),
			'site' => array(
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_REQUIRED => true,
			),
			'token' => array(
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_REQUIRED => true,
			),
			'dmap' => array(
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_REQUIRED => 'true',
			),
		);
	}
}
