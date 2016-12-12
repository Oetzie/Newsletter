<?php

	/**
	 * Newsletter
	 *
	 * Copyright 2016 by Oene Tjeerd de Bruin <info@oetzie.nl>
	 *
	 * This file is part of Newsletter, a real estate property listings component
	 * for MODX Revolution.
	 *
	 * Newsletter is free software; you can redistribute it and/or modify it under
	 * the terms of the GNU General Public License as published by the Free Software
	 * Foundation; either version 2 of the License, or (at your option) any later
	 * version.
	 *
	 * Newsletter is distributed in the hope that it will be useful, but WITHOUT ANY
	 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
	 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
	 *
	 * You should have received a copy of the GNU General Public License along with
	 * Newsletter; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
	 * Suite 330, Boston, MA 02111-1307 USA
	 */
	 
	require_once dirname( __FILE__ ).'/newsletter.class.php';

	class NewsletterCronjob extends Newsletter {	    
		/**
		 * @acces protected.
		 * @var String.
		 */
		protected $token = '';
		
		/**
		 * @acces protected.
		 * @var Integer.
		 */
	    protected $newsletterID = null;
	    
	    /**
		 * @acces protected.
		 * @var String.
		 */
	    protected $newsletterFilter = null;
	    
	    /**
		 * @acces protected.
		 * @var Boolean.
		 */
	    protected $debugMode = false;
	    
	    /**
		 * @acces protected.
		 * @var Array.
		 */
	    protected $logs = array(
		    'log'	=> array(),
		    'html'	=> array(),
		    'clean'	=> array()
	    );
	    
	    /**
		 * @acces public.
		 * @param Object $modx.
		 * @param Array $config.
		*/
		public function __construct(modX &$modx, array $config = array()) {
			parent::__construct($modx, $config);
		}
		
		/**
		 * @acces public.
		 * @param String $token.
		 * @return Boolean.
		 */
		public function setToken($token) {
			$this->token = $token;
			
			return true;
		}
		
		/**
		 * @acces public.
		 * @return String.
		 */
		public function getToken() {
			return $this->token;
		}
		
		/**
		 * @acces public.
		 * @param Integer $newsletterID.
		 * @return Boolean.
		 */
		public function setNewsletterID($newsletterID) {
			$this->newsletterID = $newsletterID;
			
			return true;
		}
		
		/**
		 * @acces public.
		 * @return Integer.
		 */
		public function getNewsletterID() {
			return $this->newsletterID;
		}
		
		/**
		 * @acces public.
		 * @param String $newsletterFilter.
		 * @retun Boolean.
		 */
		public function setNewsletterFilter($newsletterFilter) {
			$this->newsletterFilter = $newsletterFilter;
			
			return true;
		}
		
		/**
		 * @acces public.
		 * @return String.
		 */
		public function getNewsletterFilter() {
			return $this->newsletterFilter;
		}
		
		/**
	     * @acces public.
	     * @param Boolean $debugMode.
	     * @return Boolean.
	     */
	    public function setDebugMode($debugMode) {
	        if ($debugMode) {
	            $this->log('Debug mode is enabled. No database queries or send actions will be executed.', 'notice');
	        }
	
	        $this->debugMode = $debugMode;
	
	        return true;
	    }
	
	    /**
	     * @acces public.
	     * @return Boolean.
	     */
	    public function getDebugMode() {
	        return $this->debugMode;
	    }
	    
		/**
	     * @acces protected.
	     * @param String $message.
	     * @param String $level.
	     * @return Boolean.
	     */
		protected function log($message, $level = 'info') {
	        switch ($level) {
	            case 'error':
	                $prefix = 'ERROR::';
	                $color = 'red';
	                break;
	            case 'notice':
	                $prefix = 'NOTICE::';
	                $color = 'yellow';
	                break;
	            case 'success':
	                $prefix = 'SUCCESS::';
	                $color = 'green';
	                break;
	            default:
	                $prefix = 'INFO::';
	                $color = 'blue';
	                
	                break;
	        }
	
	        $log 	= $this->colorize($prefix, $color).' '.$message;
	        $html 	= '<span style="color: '.$color.'">'.$prefix.'</span> '.$message;
	
	        if (XPDO_CLI_MODE) {
	            $this->modx->log(MODX_LOG_LEVEL_INFO, $log);
	        } else {
	            $this->modx->log(MODX_LOG_LEVEL_INFO, $html);
	        }
	
	        /*
	         * logMessage has CLI markup
	         * htmlMessage has HTML markup
	         * cleanMessage has no markup
	         */
	        $this->logs['log'][]   = $log;
	        $this->logs['html'][]  = $html;
	        $this->logs['clean'][] = $prefix.' '.$message;
	
	        return true;
	    }
	    
	    /**
	     * @acces protected.
	     * @param String $string.
	     * @param String $color.
	     * @return String.
	     */
	    protected function colorize($string, $color = 'white') {
	        switch ($color) {
	            case 'red':
	                return "\033[31m".$string."\033[39m";
	                
	                break;
	            case 'green':
	                return "\033[32m".$string."\033[39m";
	                
	                break;
	            case 'yellow':
	                return "\033[33m".$string."\033[39m";
	                
	                break;
	            case 'blue':
	                return "\033[34m".$string."\033[39m";
	                
	                break;
	            default:
	                return $string;
	                
	                break;
	        }
	    }
	    
	    /**
		 * @acces public.
		 * @return Boolean.
		 */
		public function run() {
			if (null !== ($mail = $this->modx->getService('mail', 'mail.modPHPMailer'))) {
				if ($this->getToken() == $this->modx->getOption('token', $this->config, null)) {
					$newsletters = array();
					
					if (null !== ($id = $this->getNewsletterID())) {
						$criterea = array(
							'id' => $id	
						);
						
						if (null !== ($newsletter = $this->modx->getObject('NewsletterNewsletters', $criterea))) {
							$newsletters[] = $newsletter;
						}
					} else {
						foreach ($this->modx->getCollection('NewsletterNewsletters') as $newsletter) {
							if (true === $newsletter->getSendStatus()) {
								$newsletters[] = $newsletter;
							}
						}
					}
					
					$this->log(count($newsletters).' newsletter(s) ready to send.', 'info');
					
					foreach ($newsletters as $key => $newsletter) {
						$resource = $newsletter->getNewsletterResource();
						
						$this->log('Newsletter '.($key + 1).' of '.count($newsletters).' "'.$resource->pagetitle.'" ready to send.', 'info');
						
						$emails = array();

						foreach ($newsletter->getSubscriptions() as $list => $subscriptions) {
							if (null !== ($filter = $this->getNewsletterFilter())) {
								$subscriptions = $modx->runSnippet($filter, array(
									'subscriptions' => $subscriptions
								));
							}
									
							if ('emails' == $list) {
								$this->log('Sending to individual email addresses', 'info');
							} else {
								$this->log('Sending to list "'.$list.'".', 'info');
							}
							
							$count = 0;
							
							foreach ($subscriptions as $subscription) {
								if (!in_array($subscription['email'], $emails)) {
									$placeholdes = array();
											
									foreach ($subscription as $key => $value) {
										$placeholders['subscribe_'.$key] = $value;	
									}
									
									$placeholders = array_merge(array(
										'newsletter_url'	=> 	$this->modx->makeUrl($resource->id, $resource->context_key, $placeholders, 'full')
									), $placeholders);
									
									$mail->setHTML(true);
									
						    		$mail->set(modMail::MAIL_FROM, 		$this->modx->getOption('sender_email', $this->config));
									$mail->set(modMail::MAIL_FROM_NAME, $this->modx->getOption('sender_name', $this->config));
									$mail->set(modMail::MAIL_BODY, 		$newsletter->getNewsletter($this->modx, 'content', $placeholders));
									$mail->set(modMail::MAIL_SUBJECT, 	$newsletter->getNewsletter($this->modx, 'title', $placeholders));
									
									$mail->address('to', $subscription['email']);
									
									if (!$this->getDebugMode()) {
										if (!$mail->send()) {
											$this->log(($count + 1).' of '.count($subscriptions).': '.$subscription['email'].', not send because an email error.', 'error');
										} else {
											$this->log(($count + 1).' of '.count($subscriptions).': '.$subscription['email'].', send.', 'info');
										}
									} else {
										$this->log(($count + 1).' of '.count($subscriptions).': '.$subscription['email'].', send.', 'info');
									}
							
									$mail->reset();
	
									$emails[] = $subscription['email'];	
								} else {
									$this->log(($count + 1).' of '.count($subscriptions).': '.$subscription['email'].', not send because an email duplicate.', 'notice');
								}
								
								$count++;
							}
						}
						
						$this->log('Newsletter '.($key + 1).' of '.count($newsletters).' "'.$resource->pagetitle.'" send to '.count($emails).' emails.', 'info');
						
						$newsletter->sent_status = 1;
						$newsletter->send_repeat = (int) $newsletter->send_repeat - 1;
						
						if (0 < $newsletter->send_repeat) {
							$newsletter->send_status = 1;
							$newsletter->send_date = date('Y-m-d', strtotime('+'.$newsletter->send_interval.' days'));
						} else {
							$newsletter->send_status = 2;
							$newsletter->send_date = date('Y-m-d');
						}
						
						if (!$this->getDebugMode()) {
							$newsletter->setSendDetail($this->modx, $emails);
							
							$newsletter->save();
						}
					}
				} else {
					$this->log('No valid newsletter token', 'error');
				}
			} else {
				$this->log('Cannot initialize service mail service.');
			}

			return true;
		}
	}
	
?>