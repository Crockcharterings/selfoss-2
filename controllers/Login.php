<?PHP

namespace controllers;

/**
 * Controller for root
 *
 * @package    controllers
 * @copyright  Copyright (c) Tobias Zeising (http://www.aditu.de)
 * @license    GPLv3 (http://www.gnu.org/licenses/gpl-3.0.html)
 * @author     Tobias Zeising <tobias.zeising@aditu.de>
 */
class Login extends BaseController {
	public function index()
	{
		session_start();
		if ($_POST) {
			$guid = $_POST['guid'];
			$_SESSION['nickname'] = $this->getGuid($guid);
			header("Location:/");
		} else {
	        echo $this->view->render('templates/guid.phtml');
		}
	}
	public function getGuid($guid)
	{
		$mysqli = new \mysqli(\F3::get('db_host'), \F3::get('db_username'), \F3::get('db_password'), \F3::get('db_database'));
        $mysqli->set_charset("utf8");
        $sql    = "SELECT * FROM company where guid='" . $guid . "'";
        $result = mysqli_query($mysqli, $sql);
        while($row = mysqli_fetch_array($result))
        {
        	$company_name_s = $row['company_name_s'];
        }
        mysqli_close($mysqli); 
        return $company_name_s;
	}
	public function logout()
	{
		session_start();
		session_destroy();
		header("Location:/");
	}

}