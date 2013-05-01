<?php
if (isset($_POST['Upload'])) {

			$target_path = DVWA_WEB_PAGE_TO_ROOT."hackable/uploads/";
			$target_path = $target_path . basename($_FILES['uploaded']['name']);
			$uploaded_name = $_FILES['uploaded']['name'];
			$uploaded_ext = substr($uploaded_name, strrpos($uploaded_name, '.') + 1);
			$uploaded_size = $_FILES['uploaded']['size'];

			if (($uploaded_ext == "jpg" || $uploaded_ext == "JPG" || $uploaded_ext == "jpeg" || $uploaded_ext == "JPEG") && ($uploaded_size < 100000)){


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
			
			else{
				
				$html .= '<pre>';
				$html .= 'Your image was not uploaded.';
				$html .= '</pre>';

			}
		}

?>