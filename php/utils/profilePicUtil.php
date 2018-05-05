<?php
	/* Questo file contiene alcune funzioni per la gestione delle immagini del profilo */

	// Resituisce il tag img corrispondente all'utente passato
	function getPic($id) {
		// Tag da restituire di default se l'utente non ha una immagine del profilo
		$default_pic = '<img src="./../images/user-default.jpeg" alt="Profile Pic">';

		if ($id == null) {
			return $default_pic;
		}

		// Interroga il database riguardo all'immagine utente
		$type = id2Pic($id);

		if($type == null ) {// Se non risponde
			return $default_pic;
		}

		$type = $type->fetch_assoc();

		if (!$type['Image']) {// se La risposta è null
			return $default_pic;
		}

		// L'immagine del profilo si trova nella cartella uploads
		// Il nome del file è l'hash dell'id dell'utente
		return '<img src="./../uploads/'.hash('sha256', $id).'" alt="Profile Pic" >';
	}
?>