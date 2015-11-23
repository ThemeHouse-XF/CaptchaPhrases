<?php
						
class ThemeHouse_CaptchaPhrases_Extend_XenForo_Model_CaptchaQuestion extends XFCP_ThemeHouse_CaptchaPhrases_Extend_XenForo_Model_CaptchaQuestion
{
	/**
	 * @see XenForo_Model_CaptchaQuestion::prepareCaptchaQuestion()
	 */
	public function prepareCaptchaQuestion(array $captchaQuestion)
	{
		$captchaQuestion = parent::prepareCaptchaQuestion($captchaQuestion);
	
		$captchaQuestion['question'] = new XenForo_Phrase(
			'captcha_question_' . $captchaQuestion['captcha_question_id']
		);
		
		return $captchaQuestion;
	}
}