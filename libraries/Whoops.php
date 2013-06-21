<?php

/**
 *  Whoops Library for CodeIgniter
 *
 * 	@author 	Philipp Heyse <philipp.heyse@googlemail.com> 
 *	@copyright	2013, netvigation GbR - http://www.netvigation.de
 *	@license 	http://www.freebsd.org/copyright/freebsd-license.html  BSD License (2 Clause)
 *  @link 		https://github.com/netvigation-heysep/codeigniter-whoops-lib
 *
 *	All rights reserved.
 *
 *	Redistribution and use in source and binary forms, with or without modification, are 
 *  permitted provided that the following conditions are met:
 *
 *	Redistributions of source code must retain the above copyright notice, this list of 
 *  conditions and the following disclaimer.
 *	Redistributions in binary form must reproduce the above copyright notice, this list 
 *  of conditions and the following disclaimer in the documentation and/or other materials
 *  provided with the distribution.
 *
 *  THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY 
 *  EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF 
 *  MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL
 *  THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, 
 *  SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT
 *  OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION)
 *  HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 *  LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 *  OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 **/


use Whoops\Run;

class Whoops
{

	public $whoops;

	public function __construct()
	{
		$CI = &get_instance();

		require_once __DIR__ . '/Whoops/Run.php'; 

		/**
		 * if you use CI with Doctrine Library, you can use their Classloader
		 **/
		// require_once __DIR__ . '/Doctrine/Common/ClassLoader.php';

		/**
		 *
		 * For those who don't, the Doctrine Classloader is included
		 **/
		require_once __DIR__ . '/_Classloader.php';
		
		$loader = new ClassLoader('Whoops', __DIR__);
		$loader->register();
		
		$whoops = new Whoops\Run();



		// Configure the PrettyPageHandler:
		$errorPage = new Whoops\Handler\PrettyPageHandler();
				
		$errorPage->setPageTitle("It's broken!"); // Set the page's title
		$errorPage->setEditor("sublime");         // Set the editor used for the "Open" link
		$errorPage->addDataTable("Extra Info", array(
			"stuff" => 123,
			"foo"   => "bar",
			"route" => $CI->uri->uri_string()
		));
		
		$whoops->pushHandler($errorPage);	 	
				
		if ($CI->input->is_ajax_request() == true) {
			$whoops->pushHandler(new Whoops\Handler\JsonResponseHandler());
		}
		
		
		$whoops->register();
		$this->whoops = $whoops;
	}

}
