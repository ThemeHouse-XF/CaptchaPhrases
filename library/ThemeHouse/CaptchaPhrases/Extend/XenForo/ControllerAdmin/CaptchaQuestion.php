<?php
						
class ThemeHouse_CaptchaPhrases_Extend_XenForo_ControllerAdmin_CaptchaQuestion extends XFCP_ThemeHouse_CaptchaPhrases_Extend_XenForo_ControllerAdmin_CaptchaQuestion
{
	public function actionAdd()
	{
		$response = parent::actionAdd();
		
		if (get_class($response) == 'XenForo_ControllerResponse_View')
		{
			/* @var $phraseModel XenForo_Model_Phrase */
			$phraseModel = XenForo_Model::create('XenForo_Model_Phrase');
			
			$response->params['phrases'] = 
				$phraseModel->getEffectivePhraseValuesInAllLanguages(array('question'));
			
			/* @var $languageModel XenForo_Model_Language */
			$languageModel = XenForo_Model::create('XenForo_Model_Language');
				
			$response->params['languages'] = $languageModel->getAllLanguagesAsFlattenedTree();
			
			$response->params['questionPhraseTitle'] =
				'captcha_question_' . $response->params['captchaQuestion']['captcha_question_id'];
		}
		
		return $response;
	}
	
	public function actionEdit()
	{
		$response = parent::actionEdit();
		
		if (get_class($response) == 'XenForo_ControllerResponse_View')
		{
			/* @var $phraseModel XenForo_Model_Phrase */
			$phraseModel = XenForo_Model::create('XenForo_Model_Phrase');
			
			$questionPhraseTitle = 'captcha_question_' . $response->params['captchaQuestion']['captcha_question_id'];
			
			$response->params['phrases'] = 
				$phraseModel->getEffectivePhraseValuesInAllLanguages(array('question', $questionPhraseTitle));
			
			/* @var $languageModel XenForo_Model_Language */
			$languageModel = XenForo_Model::create('XenForo_Model_Language');
				
			$response->params['languages'] = $languageModel->getAllLanguagesAsFlattenedTree();
			
			$response->params['questionPhraseTitle'] = $questionPhraseTitle;
		}
		
		return $response;
	}
	
	/**
	 * @see XenForo_ControllerAdmin_CaptchaQuestion::actionSave()
	 */
	public function actionSave()
	{
		$GLOBALS['ThemeHouse_CaptchaPhrases_Extend_XenForo_ControllerAdmin_CaptchaQuestion'] = $this;
		
		return parent::actionSave();
	}
	
	public function processCaptchaPhrases(ThemeHouse_CaptchaPhrases_Extend_XenForo_DataWriter_CaptchaQuestion $dw)
	{
		$phrases = $this->_input->filterSingle('phrases', XenForo_Input::ARRAY_SIMPLE);
		
		/* @var $languageModel XenForo_Model_Language */
		$languageModel = XenForo_Model::create('XenForo_Model_Language');
			
		$languages = $languageModel->getAllLanguagesAsFlattenedTree();
		
		$languagePhrases = $dw->getExtraData(ThemeHouse_CaptchaPhrases_Extend_XenForo_DataWriter_CaptchaQuestion::DATA_LANGUAGE_PHRASES);

		foreach ($languages as $languageId => $language)
		{
			/* @var $writer XenForo_DataWriter_Phrase */
			$writer = XenForo_DataWriter::create('XenForo_DataWriter_Phrase');
			if (isset($languagePhrases[$languageId]))
			{
				$writer->setExistingData($languagePhrases[$languageId]);
				if (!isset($phrases[$languageId]) || !$phrases[$languageId])
				{
					$writer->delete();
					continue;
				}
			}
			else
			{
				$writer->bulkSet(array(
					'language_id' => $languageId,
					'title' => 'captcha_question_' . $dw->get('captcha_question_id'),
					'global_cache' => 0,
				));
			}
			if (isset($phrases[$languageId]) && $phrases[$languageId])
			{
				$writer->set('phrase_text', $phrases[$languageId]);
				$writer->save();
			}
		}
	}
}