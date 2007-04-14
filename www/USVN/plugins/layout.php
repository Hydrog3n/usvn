<?php
/**
 * Add header and footer to USVN pages
 *
 * @author Team USVN <contact@usvn.info>
 * @link http://www.usvn.info
 * @license http://www.cecill.info/licences/Licence_CeCILL_V2-en.txt CeCILL V2
 * @copyright Copyright 2007, Team USVN
 * @since 0.5
 * @package usvn
 *
 * This software has been written at EPITECH <http://www.epitech.net>
 * EPITECH, European Institute of Technology, Paris - FRANCE -
 * This project has been realised as part of
 * end of studies project.
 *
 * $Id$
 */
class layout extends Zend_Controller_Plugin_Abstract
{
	/**
	 * Doit gerer le layout de la page HTML
	 *
	 */
	public function dispatchLoopShutdown()
	{
		$response = $this->getResponse();
		$headers = $response->getHeaders();
		foreach ($headers as $header) {
			if (strcasecmp($header['name'], "Content-Type") == 0) {
				if (strcasecmp($header['value'], "text/html") == 0) {
					$this->addHeader($response);
					$this->addFooter($response);
				}
				break;
			}
		}
	}

	/**
	 * Add header text
	 *
	 * @param Zend_Controller_Response_Abstract $response
	 */
	protected function addHeader($response)
	{
		$base_url = $this->getRequest()->getBaseUrl();
		$header = <<<EOF
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="fr" xml:lang="fr">
	<head>
	    <title>USVN</title>
	    <link type="text/css" rel="stylesheet" media="screen" href="{$base_url}/css/" />
		<link type="text/css" rel="stylesheet" media="print" href="{$base_url}/medias/css/print.css"  />
		<link rel="icon" href="{$base_url}/medias/images/USVN.ico" type="image/x-icon" />
	    <meta http-equiv="Content-Type"	content="text/html; charset=UTF-8" />
	    <script language="JavaScript" type="text/javascript" src="{$base_url}/medias/js/usvn.js"></script>
	</head>
	<body>
		<div id="usvn_banner">
			<div id="usvn_header">
				<a id="usvn_logo" href="{$base_url}">
					<img src="{$base_url}/medias/images/USVN-logo.png" alt="USVN, Userfriendly SVN" />
				</a>
			</div>
		</div>
		<div id="usvn_menu"></div>
		<div id="usvn_content">
EOF;
		$body = $response->getBody(true);
		$response->setBody($header);
		foreach ($body as $text) {
			$response->appendBody($text);
		}
	}

	/**
	 * Add footer text
	 *
	 * @param Zend_Controller_Response_Abstract $response
	 */
	protected function addFooter($response)
	{
		$footer = "";
		$profiler = Zend_Db_Table::getDefaultAdapter()->getProfiler();
		if (is_array($profiler->getQueryProfiles())) {
			$footer .= "	</div>
								<div id=\"usvn_debug\">
									<div>View debug
									<div id=\"usvn_debug_view\">
							";
			foreach ($profiler->getQueryProfiles() as $query) {
				/* @var $query Zend_Db_Profiler_Query */
				$footer .= "<dl>";
				$footer .= "<dt>" . $query->getElapsedSecs() . "</dt>";
				$footer .= "<dd>" . $query->getQuery() . "</dd>";
				$footer .= "</dl>";
			}
		}
		$footer .= <<<EOF
				</div>
			</div>
		</div>
		<div id="usvn_footer">Powered by USVN</div>
	</body>
</html>
EOF;
		$response->appendBody($footer);
	}
}
