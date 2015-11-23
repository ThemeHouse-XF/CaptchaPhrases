<?php
						
class ThemeHouse_CaptchaPhrases_Extend_XenForo_DataWriter_CaptchaQuestion extends XFCP_ThemeHouse_CaptchaPhrases_Extend_XenForo_DataWriter_CaptchaQuestion
{
	const THEMEHOUSE_CAPTCHAPHRASES_EXTEND_XENFORO_CONTROLLERADMIN_CAPTCHAQUESTION = 'ThemeHouse_CaptchaPhrases_Extend_XenForo_ControllerAdmin_CaptchaQuestion';

	const DATA_LANGUAGE_PHRASES = 'languagePhrases';
	
	/**
	 * @return array
	 */
	protected function _getDefaultOptions()
	{
		if (isset($GLOBALS['ThemeHouse_CaptchaPhrases_Extend_XenForo_ControllerAdmin_CaptchaQuestion']))
		{
			$this->setExtraData(self::THEMEHOUSE_CAPTCHAPHRASES_EXTEND_XENFORO_CONTROLLERADMIN_CAPTCHAQUESTION,
					$GLOBALS['ThemeHouse_CaptchaPhrases_Extend_XenForo_ControllerAdmin_CaptchaQuestion']);
			unset($GLOBALS['ThemeHouse_CaptchaPhrases_Extend_XenForo_ControllerAdmin_CaptchaQuestion']);
		}

		return parent::_getDefaultOptions();
	}

	protected function _postSave()
	{
		/* @var $phraseModel XenForo_Model_Phrase */
		$phraseModel = XenForo_Model::create('XenForo_Model_Phrase');

		$visitor = XenForo_Visitor::getInstance();
		$visitorLanguage = $visitor->getLanguage();
		
		$languagePhrases = $phraseModel->getPhraseIdInLanguagesByTitle(
			'captcha_question_' . $this->get('captcha_question_id')
		);
		$this->setExtraData(self::DATA_LANGUAGE_PHRASES, $languagePhrases);
		
		if ($this->isExtraDataSet(self::THEMEHOUSE_CAPTCHAPHRASES_EXTEND_XENFORO_CONTROLLERADMIN_CAPTCHAQUESTION))
		{
			$this->getExtraData(self::THEMEHOUSE_CAPTCHAPHRASES_EXTEND_XENFORO_CONTROLLERADMIN_CAPTCHAQUESTION)->processCaptchaPhrases($this);
		}
					
		/* @var $writer XenForo_DataWriter_CaptchaQuestion */
		$writer = XenForo_DataWriter::create('XenForo_DataWriter_Phrase');
		if (isset($languagePhrases[0]))
		{
			$writer->setExistingData($languagePhrases[0]);
		}
		else
		{
			$writer->bulkSet(array(
				'language_id' => 0,
				'title' => 'captcha_question_' . $this->get('captcha_question_id'),
				'global_cache' => 0,
			));
		}
		$writer->set('phrase_text', $this->get('question'));
		$writer->save();
	
		parent::_postSave();
	}
}