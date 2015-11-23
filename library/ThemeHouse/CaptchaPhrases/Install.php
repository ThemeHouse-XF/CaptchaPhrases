<?php

class ThemeHouse_CaptchaPhrases_Install extends ThemeHouse_Install
{
	protected function _postInstall()
	{
		/* @var $captchaQuestionModel XenForo_Model_CaptchaQuestion */
		$captchaQuestionModel = XenForo_Model::create('XenForo_Model_CaptchaQuestion');
		
		$captchaQuestions = $captchaQuestionModel->getAllCaptchaQuestions();
		
		/* @var $phraseModel XenForo_Model_Phrase */
		$phraseModel = XenForo_Model::create('XenForo_Model_Phrase');
		
		$phrases = $phraseModel->getEffectivePhraseListForLanguage(0, array(
			'title' => 'captcha_question_'
		));
		
		$phraseTitles = array();
		foreach ($phrases as $phrase)
		{
			$phraseTitles[substr($phrase['title'], strlen('captcha_question'))]
				= $phrase['phrase_id'];
		}
		
		foreach ($captchaQuestions as $captchaQuestion)
		{
			$data = array(
				'title' => 'captcha_question_' . $captchaQuestion['captcha_question_id'],
				'phrase_text' => $captchaQuestion['question'],
				'language_id' => 0,
				'global_cache' => 0,
			);
			
			/* @var $writer XenForo_DataWriter_Phrase */
			$writer = XenForo_DataWriter::create('XenForo_DataWriter_Phrase');
			
			$phraseId = array_search(
				'captcha_question' . $captchaQuestion['captcha_question_id'],
				$phraseTitles
			);
			if ($phraseId) $writer->setExistingData($phraseId);
			
			$writer->bulkSet($data);
			
			if ($writer->isChanged('title') || $writer->isChanged('phrase_text'))
			{
				$writer->updateVersionId();
			}
			
			$writer->save();
		}
	}
}