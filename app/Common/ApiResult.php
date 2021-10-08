<?php
namespace App\Common;

class ApiResult
{
	private $retCode;
	private $retMsg;
	
	public function fill ($retCode, $retMsg)
	{
		$this->retCode = $retCode;
		$this->retMsg = $retMsg;
	}

	public function report ()
	{
		$report_items = func_get_args();
		$report = ['return_code' => $this->getRetCode(), 'return_message' => $this->getRetMsg()];
		foreach ($report_items as $item)
		{
			$report[$item] = $this->$item;
		}
		
		return $report;
	}

	/**
	 * Get the value of retCode
	 */ 
	public function getRetCode()
	{
		return $this->retCode;
	}

	/**
	 * Set the value of retCode
	 *
	 * @return  self
	 */ 
	public function setRetCode($retCode)
	{
		$this->retCode = $retCode;

		return $this;
	}

	/**
	 * Get the value of retMsg
	 */ 
	public function getRetMsg()
	{
		return $this->retMsg;
	}

	/**
	 * Set the value of retMsg
	 *
	 * @return  self
	 */ 
	public function setRetMsg($retMsg)
	{
		$this->retMsg = $retMsg;

		return $this;
	}
}