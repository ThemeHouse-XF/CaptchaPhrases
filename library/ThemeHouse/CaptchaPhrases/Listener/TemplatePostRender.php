<?php

class ThemeHouse_CaptchaPhrases_Listener_TemplatePostRender extends ThemeHouse_Listener_TemplatePostRender
{
	public function run() {
		switch ($this->_templateName)
		{
			case 'captcha_question_edit':
				$this->_captchaQuestionEdit();
				break;
		}
		return parent::run();
	}

	public static function templatePostRender($templateName, &$content, array &$containerData, XenForo_Template_Abstract $template)
	{
		$templatePostRender = new ThemeHouse_CaptchaPhrases_Listener_TemplatePostRender($templateName, $content, $containerData, $template);
		list($content, $containerData) = $templatePostRender->run();
	}

	protected function _captchaQuestionEdit()
	{
		$codeSnippet = str_replace('th_replace_', '', $this->_render('th_question_edit_replace_captchaphrases'));
		$template = str_replace('th_', '', $this->_render('th_question_edit_captchaphrases'));
		$this->_replaceAtCodeSnippet($codeSnippet, $template);
	}
}