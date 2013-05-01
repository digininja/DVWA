<?php
	if (isset($_POST['Upload'])) {

			$target_path = DVWA_WEB_PAGE_TO_ROOT."hackable/uploads/";
			$target_path = $target_path . basename( $_FILES['uploaded']['name']);

			if(!move_uploaded_file($_FILES['uploaded']['tmp_name'], $target_path)) {
				
				$html .= '<pre>';
				$html .= 'Your image was not uploaded.';
				$html .= '</pre>';
				
      		} else {
			
				$html .= '<pre>';
				$html .= $target_path . ' succesfully uploaded!';
				$html .= '</pre>';
				
			}

		}
?>