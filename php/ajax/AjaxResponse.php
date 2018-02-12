<?php
class AjaxResponse {
	public $responseCode;
	public $message;
	public $data;

	function AjaxResponse($responseCode=-1,
						  $message="Something went wrong!",
						  $data = null) {
		$this->responseCode= $responseCode;
		$this->message = $message;
		$this->data = $data;
	}
}
?>