<?php
/**
 * Licensed to the Apache Software Foundation (ASF) under one or more
 * contributor license agreements. See the NOTICE file distributed with
 * this work for additional information regarding copyright ownership.
 * The ASF licenses this file to You under the Apache License, Version 2.0
 * (the "License"); you may not use this file except in compliance with
 * the License. You may obtain a copy of the License at
 *
 *	   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 *
 * @package log4php
 * @subpackage appenders
 */

/**
 * LoggerAppenderEcho uses {@link PHP_MANUAL#echo echo} function to output events. 
 * 
 * <p>This appender requires a layout.</p>	
 *
 * @version $Revision: 771557 $
 * @package log4php
 * @subpackage appenders
 */
class LoggerAppenderEcho extends LoggerAppender {
	/** boolean used internally to mark first append */
	private $firstAppend = true;
	
	public function __construct($name) {
	    parent::__construct($name);
	    $this->requiresLayout = true;
	    $this->firstAppend = true;
	}
	
	public function activateOptions() {
		$this->closed = false;
	}
	
	public function close() {
		if(!$this->firstAppend) {
			echo $this->layout->getFooter();
		}
		$this->closed = true;	 
	}

	public function append($event) {
		if($this->layout !== null) {
			if($this->firstAppend) {
				echo $this->layout->getHeader();
				$this->firstAppend = false;
			}
			echo $this->layout->format($event);
		} 
	}
}

