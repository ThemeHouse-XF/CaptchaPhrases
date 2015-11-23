<?php
						
class ThemeHouse_CaptchaPhrases_Listener_LoadClassController extends ThemeHouse_Listener_LoadClass
{
	/**
	 * Gets the classes that are extended for this add-on. See parent for explanation.
	 * 
	 * @return array
	 */
	protected function _getExtends()
	{
		return array(
			'XenForo_ControllerAdmin_CaptchaQuestion' => 'ThemeHouse_CaptchaPhrases_Extend_XenForo_ControllerAdmin_CaptchaQuestion', /* END 'XenForo_ControllerAdmin_CaptchaQuestion' */
		);
	} /* END ThemeHouse_CaptchaPhrases_Listener_LoadClassController::_getExtends */

	public static function loadClassController($class, array &$extend)
	{
		$loadClassController = new ThemeHouse_CaptchaPhrases_Listener_LoadClassController($class, $extend);
		$extend = $loadClassController->run();
	} /* END ThemeHouse_CaptchaPhrases_Listener_LoadClassController::loadClassController */
}