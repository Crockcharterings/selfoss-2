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
class Index extends BaseController {

    /**
     * home site
     * html
     *
     * @return void
     */
    public function home() {
        // check login
        $this->authentication();
        
        // parse params
        $options = array();
        if (\F3::get('homepage')!='')
            $options = array( 'type' => \F3::get('homepage') );
        
        // use ajax given params?
        if(count($_GET)>0)
            $options = $_GET;
        
        // get search param
        if(isset($options['search']) && strlen($options['search'])>0)
            $this->view->search = $options['search'];
        
        // load tags
        $tagsDao = new \daos\Tags();
        $tags = $tagsDao->getWithUnread();
        
        // load items
        $itemsHtml = $this->loadItems($options, $tags);
        $this->view->content = $itemsHtml;
        
        // load stats
        $itemsDao = new \daos\Items();
        $stats = $itemsDao->stats();
        $this->view->statsAll = $stats['total'];
        $this->view->statsUnread = $stats['unread'];
        $this->view->statsStarred = $stats['starred'];

        if ($tagsDao->hasTag("#")) {
            foreach ($tags as $tag) {
            if (strcmp($tag["tag"], "#") !== 0) {
                continue;
            }
            $this->view->statsUnread -= $tag["unread"];
        }
	}

        // prepare tags display list
        $tagsController = new \controllers\Tags();
        $this->view->tags = $tagsController->renderTags($tags);
        
        if(isset($options['sourcesNav']) && $options['sourcesNav'] == 'true' ) {
            // prepare sources display list
            $sourcesDao = new \daos\Sources();
            $sources = $sourcesDao->getWithUnread();
            $sourcesController = new \controllers\Sources();
            $this->view->sources = $sourcesController->renderSources($sources);
        } else
            $this->view->sources = '';
        
        // ajax call = only send entries and statistics not full template
        if(isset($options['ajax'])) {
            $this->view->jsonSuccess(array(
                "entries"  => $this->view->content,
                "all"      => $this->view->statsAll,
                "unread"   => $this->view->statsUnread,
                "starred"  => $this->view->statsStarred,
                "tags"     => $this->view->tags,
                "sources"  => $this->view->sources
            ));
        }
        
        // show as full html page
        $this->view->publicMode = \F3::get('auth')->isLoggedin()!==true && \F3::get('public')==1;
        $this->view->loggedin = \F3::get('auth')->isLoggedin()===true;
        echo $this->view->render('templates/home.phtml');
    }
    
    public function adminhome() {
        // check login
        $this->authentication();
        
        // parse params
        $options = array();
        if (\F3::get('homepage')!='')
            $options = array( 'type' => \F3::get('homepage') );
        
        // use ajax given params?
        if(count($_GET)>0)
            $options = $_GET;
        
        // get search param
        if(isset($options['search']) && strlen($options['search'])>0)
            $this->view->search = $options['search'];
        
        // load tags
        $tagsDao = new \daos\Tags();
        $tags = $tagsDao->getWithUnread();
        
        // load items
        $itemsHtml = $this->loadItems($options, $tags);
        $this->view->content = $itemsHtml;
        
        // load stats
        $itemsDao = new \daos\Items();
        $stats = $itemsDao->stats();
        $this->view->statsAll = $stats['total'];
        $this->view->statsUnread = $stats['unread'];
        $this->view->statsStarred = $stats['starred'];

        if ($tagsDao->hasTag("#")) {
        foreach ($tags as $tag) {
            if (strcmp($tag["tag"], "#") !== 0) {
                continue;
            }
            $this->view->statsUnread -= $tag["unread"];
        }
    }

        // prepare tags display list
        $tagsController = new \controllers\Tags();
        $this->view->tags = $tagsController->renderTags($tags);
        
        if(isset($options['sourcesNav']) && $options['sourcesNav'] == 'true' ) {
            // prepare sources display list
            $sourcesDao = new \daos\Sources();
            $sources = $sourcesDao->getWithUnread();
            $sourcesController = new \controllers\Sources();
            $this->view->sources = $sourcesController->renderSources($sources);
        } else
            $this->view->sources = '';
        
        // ajax call = only send entries and statistics not full template
        if(isset($options['ajax'])) {
            $this->view->jsonSuccess(array(
                "entries"  => $this->view->content,
                "all"      => $this->view->statsAll,
                "unread"   => $this->view->statsUnread,
                "starred"  => $this->view->statsStarred,
                "tags"     => $this->view->tags,
                "sources"  => $this->view->sources
            ));
        }
        
        // show as full html page
        $this->view->publicMode = \F3::get('auth')->isLoggedin()!==true && \F3::get('public')==1;
        $this->view->loggedin = \F3::get('auth')->isLoggedin()===true;
        echo $this->view->render('templates/adminhome.phtml');
    }
    /**
     * password hash generator
     * html
     *
     * @return void
     */
    public function password() {
        $this->view = new \helpers\View();
        $this->view->password = true;
        if(isset($_POST['password']))
            $this->view->hash = hash("sha512", \F3::get('salt') . $_POST['password']);
        echo $this->view->render('templates/login.phtml');
    }
    
    
    /**
     * check and show login/logout
     * html
     *
     * @return void
     */
    private function authentication() {
        // logout
        if(isset($_GET['logout'])) {
            \F3::get('auth')->logout();
            \F3::reroute($this->view->base);
        }
        
        // login
        if( 
            isset($_GET['login']) || (\F3::get('auth')->isLoggedin()!==true && \F3::get('public')!=1)
           ) {

            // authenticate?
            if(count($_POST)>0) {
                if(!isset($_POST['username']))
                    $this->view->error = 'no username given';
                else if(!isset($_POST['password']))
                    $this->view->error = 'no password given';
                else {
                    if(\F3::get('auth')->login($_POST['username'], $_POST['password'])===false)
                        $this->view->error = 'invalid username/password';
                }
            }
            
            // show login
            if(count($_POST)==0 || isset($this->view->error))
                die($this->view->render('templates/login.phtml'));
            else
                \F3::reroute($this->view->base);
        }
    }
    
    
    /**
     * login for api json access
     * json
     *
     * @return void
     */
    public function login() {
        $view = new \helpers\View();
        $username = isset($_REQUEST["username"]) ? $_REQUEST["username"] : '';
        $password = isset($_REQUEST["password"]) ? $_REQUEST["password"] : '';
        
        if(\F3::get('auth')->login($username,$password)==true)
            $view->jsonSuccess(array(
                'success' => true
            ));
        
        $view->jsonSuccess(array(
            'success' => false
        ));
    }
    

    /**
     * logout for api json access
     * json
     *
     * @return void
     */
    public function logout() {
        $view = new \helpers\View();
        \F3::get('auth')->logout();
        $view->jsonSuccess(array(
            'success' => true
        ));
    }
    
    
    /**
     * update feeds
     * text
     *
     * @return void
     */
    public function update() {
        // only allow access for localhost and loggedin users
        if (\F3::get('allow_public_update_access')!=1 
                && $_SERVER['REMOTE_ADDR'] !== $_SERVER['SERVER_ADDR'] 
                && $_SERVER['REMOTE_ADDR'] !== "127.0.0.1"
                && \F3::get('auth')->isLoggedin() != 1)
            die("unallowed access");
    
        // update feeds
        $loader = new \helpers\ContentLoader();
        $loader->update();
        $this->con();
        
        echo "finished";
    }

    /*
    * get the unread number of items for a windows 8 badge
    * notification.
    */
    public function badge() {
        // load stats
        $itemsDao = new \daos\Items();
        $this->view->statsUnread = $itemsDao->numberOfUnread();
        echo $this->view->render('templates/badge.phtml');
    }

    public function win8Notifications() {
        echo $this->view->render('templates/win8-notifications.phtml');
    }

    /**
     * load items
     *
     * @return html with items
     */
    private function loadItems($options, $tags) {
        $tagColors = $this->convertTagsToAssocArray($tags);
        
        $itemDao = new \daos\Items();
        $itemsHtml = "";
        foreach($itemDao->get($options) as $item) {
        
            // parse tags and assign tag colors
            $itemsTags = explode(",",$item['tags']);
            $item['tags'] = array();
            foreach($itemsTags as $tag) {
                $tag = trim($tag);
                if(strlen($tag)>0 && isset($tagColors[$tag]))
                    $item['tags'][$tag] = $tagColors[$tag];
            }
            
            $this->view->item = $item;
            $itemsHtml .= $this->view->render('templates/item.phtml');
        }

        if(strlen($itemsHtml)==0) {
            $itemsHtml = '<div class="stream-empty">'. \F3::get('lang_no_entries').'</div>';
        } else {
            if($itemDao->hasMore())
                $itemsHtml .= '<div class="stream-more"><span>'. \F3::get('lang_more').'</span></div>';
                $itemsHtml .= '<div class="mark-these-read"><span>'. \F3::get('lang_markread').'</span></div>';
        }
        
        return $itemsHtml;
    }
    
    
    /**
     * return tag => color array
     *
     * @return tag color array
     * @param array $tags
     */
    private function convertTagsToAssocArray($tags) {
        $assocTags = array();
        foreach($tags as $tag) {
            $assocTags[$tag['tag']]['backColor'] = $tag['color'];
            $assocTags[$tag['tag']]['foreColor'] = \helpers\Color::colorByBrightness($tag['color']);
        }
        return $assocTags;
    }
    public function html()
    {
        $this->con();
    }
    private function con() {
        $mysqli = new \mysqli(\F3::get('db_host'), \F3::get('db_username'), \F3::get('db_password'), \F3::get('db_database'));
        $mysqli->set_charset("utf8");
        $sql    = "SELECT * FROM items";
        $result = mysqli_query($mysqli, $sql);
        while($row = mysqli_fetch_array($result))
        {
            echo $row['id'] . " " . $row['title'];
            echo "<br />";
            $this->createHtml($row);
        }
        mysqli_close($mysqli);        
    }
    private function createHtml($row)
    {
        $url = \F3::get('admin_url');
        $guid = 0;
        if(isset($_SESSION['guid'])) $guid = $_SESSION['guid'];
        $myfile = fopen("data/html/".$row['id'].".html", "w") or die("Unable to open file!");
        $footer = '<!--add by lushulin-->
        <div style="width:100%;"> 
 <p style="text-align:center;font-size:15px;color:#000;">'. $_SESSION['nickname'].'该文章版权为原网站所有，本站不对原文章的版权负责。  
 <a href="'.$row['link'].'"> 
 <font color="#009">阅读原文</font></p> 
 </a> 
 </div> 
 <iframe width="100%" id="spread" frameborder="0" height="370"  src="'.$url.'/welcome/guid_spread/"></iframe>
<footer>
    <ul>
    <li></li>
    </ul>
    <div class="copyright-box">
        ©2016 微播网络
    </div>
</footer>
<script src="/../js/jquery-2.1.1.min.js"></script>
<script src="/../js/app.js"></script>
<script src="/../js/app2.js"></script>
<!--end-->';
        $header = '<iframe width="100%" id="poster" frameborder="0" height="120" src="'.$url.'/welcome/guid_poster/"></iframe>';
        $html = sprintf('<html><head><title>%s</title><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <meta content="black" name="apple-mobile-web-app-status-bar-style">
    <meta name="format-detection" content="telephone=no"/>
    <meta name="format-detection" content="email=no"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <link href="/../css/m.css" rel="stylesheet" media="screen">
    <link href="/../css/topic.css" rel="stylesheet" media="screen">
    <link href="/../css/activity.css" rel="stylesheet" media="screen">
    <link href="/../css/vip.css" rel="stylesheet" media="screen">

    </head>%s<div class="article-content">
<body><p><h2>%s</h2></p>%s%s</body></div><span value="%s"></span><script src="http://iwebo.portal.net.cn/tongji/tianda.js"></script></html>', $row['title'], $header, $row['title'], $row['content'], $footer, $guid);
        fwrite($myfile, $html);
        fclose($myfile);
        return;
    }
    public function show()
    {
        $id = \F3::get('PARAMS["id"]');
        $_SESSION['html'.$id] = 'ko';
        echo $this->view->render('data/html/'.$id.'.html');
    }
}
