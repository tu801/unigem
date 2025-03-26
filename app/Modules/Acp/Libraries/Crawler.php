<?php
/**
 * @author tmtuan
 * created Date: 29-Nov-20
 */

namespace Modules\Acp\Libraries;

use Modules\Acp\Models\AttachModel;
use Modules\Acp\Models\User\UserModel;

class Crawler
{

    const USER_AGENT = "bot";

    public $url; //URL to load DOM from
    public $url_data; //Parsed loaded URL
    public $dom; //DOM structure of loaded URL
    public $head; //DOM structure of header content
    public $article; //DOM structure of post content

    private $links;
    private $robots_rules;

    function __construct()
    {
        helper('simple_html_dom');
        $this->set_user_agent(self::USER_AGENT);

        $this->user = user();
    }

    /**
     * Set the user agent to be used for all cURL calls
     *
     * @param string    user agent string
     * @return    void
     */
    public function set_user_agent($user_agent)
    {
        ini_set('user_agent', $user_agent);
    }

    /**
     * Check to make sure URL is valid
     *
     * @param string    URL to check
     * @return    boolean    True if URL is valid. False is url is not valid.
     */
    private function check_url($url)
    {
        $headers = @get_headers($url, 0);
        if (is_array($headers)) {
            if (strpos($headers[0], '404')) {
                return false;
            }

            foreach ($headers as $header) {
                if (strpos($header, '404 Not Found')) {
                    return false;
                }
            }

            return true;
        } else {
            return false;
        }

    }

    /**
     * Set URL to scrape/crawl.
     *
     * @param string    URL to crawl
     * @return    boolean    True if URL is valid. False is URL is not valid
     */
    public function set_url($url)
    {
        $this->url = $url;

        if ((strpos($url, 'http')) === false) $url = 'http://' . $url;

        if ($this->check_url($url) === false) {
            return false;
        }
        $this->dom = file_get_html($url);
        if ( empty($this->dom) ) {
            return false;
        }

        $this->head = $this->dom->find('head', 0);

        $this->url_data = parse_url($url);
        if (empty($this->url_data['scheme'])) {
            $this->data['scheme'] == 'http';
        }
        $this->url_data['domain'] = implode(".", array_slice(explode(".", $this->url_data['host']), -2));

        if (empty($this->url_data['path']) || $this->url_data['path'] != '/robots.txt') {
            $this->get_robots();
        }

        return true;
    }

    /**
     * Retrieve and parse the loaded URL's robots.txt
     *
     * @return    array/boolean    Returns array of rules if robots.txt is valid. Otherwise returns True if no rules exist or False if robots.txt is not valid.
     */
    private function get_robots()
    {
        if (empty($this->url_data)) return false;

        $robots_url = 'http://' . $this->url_data['domain'] . '/robots.txt';

        if (!$this->check_url($robots_url)) {
            return false;
        }

        $robots_text = @file($robots_url);

        if (empty($robots_text)) {
            $this->robots_rules = false;
            return;
        }

        $user_agents = implode("|", array(preg_quote('*'), preg_quote(self::USER_AGENT)));

        $this->robots_rules = array();

        foreach ($robots_text as $line) {
            if (!$line = trim($line)) continue;

            if (preg_match('/^\s*User-agent: (.*)/i', $line, $match)) {
                $ruleApplies = preg_match("/($user_agents)/i", $match[1]);
            }
            if (!empty($ruleApplies) && preg_match('/^\s*Disallow:(.*)/i', $line, $regs)) {
                // an empty rule implies full access - no further tests required
                if (!$regs[1]) return true;
                // add rules that apply to array for testing
                $this->robots_rules[] = preg_quote(trim($regs[1]), '/');
            }
        }

        return $this->robots_rules;
    }

    /**
     * Checks robots.txt to see if a URL can be accessed.
     *
     * @param string    URL to check
     * @return    boolean    True if URL can be accessed. False if it can't.
     */
    private function check_robots($url)
    {
        if (empty($this->robots_rules)) return true;

        $parsed_url = parse_url($url);

        foreach ($this->robots_rules as $robots_rule) {
            if (preg_match("/^$robots_rule/", $parsed_url['path'])) return false;
        }

        return true;
    }

    /**
     * Removes all HTML, special characters and extra whitespace from text
     *
     * @param string    Text to be cleaned
     * @return    string    Cleaned text
     */
    private function clean_text($text)
    {
        $preg_patterns = array(
            "/[\x80-\xFF]/", //remove special characters
            "/&nbsp/",
            "/\s+/", //remove extra whitespace
        );
        $text = strip_tags(preg_replace($preg_patterns, " ", html_entity_decode($text, ENT_QUOTES, 'UTF-8')));

        return $text;
    }

    /**
     * Get HTML from loaded URL
     *
     * @return    string/boolean    If DOM is loaded returns its HTML. Otherwise returns False.
     */
    public function get_html()
    {
        if (!empty($this->dom)) {
            return $this->dom->save();
        } else {
            return false;
        }
    }

    /**
     * Get text from loaded URL without HTML tags or special characters
     *
     * @param int    Max length of text to return
     * @return    string
     */
    public function get_text($limit = null)
    {
        if (!is_null($limit) && is_numeric($limit)) {
            return substr($this->clean_text($this->dom->plaintext), 0, $limit);
        } else {
            return $this->clean_text($this->dom->plaintext);
        }
    }

    /**
     * Get title tag from loaded URL
     *
     * @return    string
     */
    public function get_title()
    {
        if ( empty($this->head) ) {
            if (!$page_title = $this->dom->find('head title', 0)) {
                return false;
            }
        } else {
            if (!$page_title = $this->head->find('title', 0)) {
                return false;
            }
        }

        return $page_title->innertext;
    }

    /**
     * Get meta description from loaded URL
     *
     * @return string
     */
    public function get_description()
    {
        if ( empty($this->head) ) {
            if (!$page_description = $this->dom->find('head meta[name=description]', 0)) {
                return false;
            }
        } else {
            if (!$page_description = $this->dom->find('meta[name=description]', 0)) {
                return false;
            }
        }

        return $page_description->content;
    }

    /**
     * Get meta keywords from loaded URL
     *
     * @return string
     */
    public function get_keywords()
    {
        if ( empty($this->head) ) {
            if (!$page_keywords = $this->dom->find('head meta[name=keywords]', 0)) {
                return false;
            }
        } else {
            if (!$page_keywords = $this->head->find('meta[name=keywords]', 0)) {
                return false;
            }
        }

        return $page_keywords->content;

    }

    /**
     * Get all links on loaded URL page
     *
     * @param array    Links containing these terms will not be returned
     * @param array    Only links containing these terms will be returned
     * @return    array    List of links on page
     */
    public function get_links($exclude_terms = array(), $include_terms = array())
    {
        if (!empty($this->links)) return $this->links;

        $this->links = array();
        $anchor_tags = $this->dom->find('a[href]');

        foreach ($anchor_tags as $anchor) {
            $anchor_url = parse_url($anchor->href);
            if ($anchor_url === false) continue;

            $anchor_href = '';
            if (empty($anchor_url['host'])) {
                if (empty($anchor_url['path'])) continue;
                $anchor_href = $this->url_data['scheme'] . '://' . $this->url_data['host'] . ((!empty($anchor_url['path']) && substr($anchor_url['path'], 0, 1) != '/') ? '/' : '') . $anchor_url['path'];
            } else {
                $anchor_domain = implode(".", array_slice(explode(".", $anchor_url['host']), -2));
                if ($anchor_domain != $this->url_data['domain']) continue;

                $anchor_href .= ((!empty($anchor_url['scheme'])) ? $anchor_url['scheme'] : 'http') . '://' . $anchor_url['host'] . ((!empty($anchor_url['path']) && substr($anchor_url['path'], 0, 1) != '/') ? '/' : '') . ((!empty($anchor_url['path'])) ? $anchor_url['path'] : '');
            }

            if ($anchor_href == $this->url || array_key_exists($anchor_href, $this->links)) continue;

            //TODO
            //Add support for relative links (ex. A link on http://passpack.com/en/home/ with an href of ../about_us should be http://passpack.com/en/about_us
            //does plaintext content exist?
            if (!empty($exclude_terms) && is_array($exclude_terms)) {
                $exclude_term_found = false;
                foreach ($exclude_terms as $term) {
                    if (stripos($this->clean_text($anchor->innertext), $term) !== false && strlen($this->clean_text($anchor->innertext)) < 50) {
                        $exclude_term_found = true;
                    }
                    if (!empty($anchor_url['path'])) {
                        $path_segments = explode("/", $anchor_url['path']);
                        $last_path_segment = array_pop($path_segments);
                        if (stripos($last_path_segment, $term) !== false && strlen($last_path_segment) < 50) {
                            $exclude_term_found = true;
                        }
                    }

                }
                if ($exclude_term_found) continue;
            }

            if (!empty($anchor_url['path']) && $this->check_robots($anchor_url['path']) !== true) {
                continue;
            }

            if (!empty($include_terms) && is_array($include_terms)) {
                $include_term_found = false;
                foreach ($include_terms as $term) {
                    if (stripos($this->clean_text($anchor->innertext), $term) !== false && strlen($this->clean_text($anchor->innertext)) < 50) {
                        $include_term_found = true;
                        continue;
                    }
                    if (!empty($anchor_url['path'])) {
                        $path_segments = explode("/", $anchor_url['path']);
                        $last_path_segment = str_replace(array('-', '_'), ' ', array_pop($path_segments));
                        if (stripos($last_path_segment, $term) !== false && strlen($last_path_segment) < 50) {
                            $include_term_found = true;
                            continue;
                        }
                    }
                }
            }

            if (isset($include_term_found) && $include_term_found) {
                $this->links[$anchor_href] = array(
                    'raw_href' => $anchor->href,
                    'full_href' => $anchor_href,
                    'text' => $this->clean_text($anchor->innertext)
                );
            }

        }

        return $this->links;
    }

    /**
     * get post content base on the site
     */
    public function get_content() {
        $site_url = parse_url($this->url);

        switch (str_replace('www.', '', $site_url['host'])) {
            case 'gameworld.vn':
                $page_content = $this->gameworld();
                break;
            case 'game8.vn':
                $page_content = $this->game8();
                break;
            case 'gamehub.vn':
                $page_content = $this->gamehub();
                break;
            case 'game4v.com':
                $page_content = $this->game4v();
                break;
            case 'gamek.vn':
                $page_content = $this->gamek();
                break;
            case 'infogame.vn':
                $page_content = $this->infogame();
                break;
            case 'techrum.vn':
                $page_content = $this->techrum();
                break;
            case 'xemgame.com':
                $page_content = $this->xemgame();
                break;
            case 'tinanime.com':
                $page_content = $this->tinanime();
                break;
        }

        if ( empty($page_content) ) return false;
        return $page_content;
    }

    /**
     * get content from gameworld
     * @return bool
     */
    private function gameworld() {
//        dd($this->dom->find('div[class=tdc-content-wrap]', 0)->children(0)->children(1));
        dd($this->dom->find('div[class=tdb-block-inner td-fix-index]'));
        if (!$content = $this->dom->find('div[class=tdb_single_content]', 0)) {
            return false;
        }
dd($content->content);
        return $content->innertext;
    }


    /**
     * get content from game8.vn
     * @return bool|string|string[]|null
     */
    private function game8() {
        if (!$content = $this->dom->find('div[id=noi_dung]', 0)) {
            return false;
        }

        // xóa content rác
        $contentData = $content->innertext;
        $contentData = str_replace('<!--PC_InRead-->', '', $contentData);
        $contentData = str_replace('<!--PC_CBV-->', '', $contentData);
        $contentData = preg_replace("/<\/?ins[^>]*\>/i",'',$contentData);
        $contentData = preg_replace('#<div class="all_slide_thecao_ud">(.*?)</div>#', '', $contentData);
        $contentData = preg_replace('#<div class="slide_thecao_ud">(.*?)</div>#', '', $contentData);
        $contentData = preg_replace('#<div class="item">(.*?)</div>#', '', $contentData);
        $contentData = preg_replace("/<div[^>]*><\\/div[^>]*>/", '', $contentData);
        $contentData = preg_replace("/<p[^>]*><\\/p[^>]*>/", '', $contentData);

        return $contentData;
    }

    /**
     * get post content from gamehub.vn
     * @return bool
     */
    private function gamehub() {
        if (!$content = $this->dom->find('article', 0)) {
            return false;
        }

        $contentData = $content->innertext;

        //get the image url
        $imgURLs = [];
        foreach($content->find('img') as $element) {
            $imgURLs[] = $element->src;
        }
        $replaceUrl = $this->getImage($imgURLs);
        $contentData = str_replace($imgURLs, $replaceUrl, $contentData);

        return $contentData;
    }

    /**
     * get post content from game4v
     * @return bool
     */
    private function game4v() {
        if (!$content = $this->dom->find('article', 0)) {
            return false;
        }

        $contentData = $content->innertext;

        //get the image url
        $imgURLs = [];
        foreach($content->find('img') as $element) {
            $imgURLs[] = $element->src;
        }
        $replaceUrl = $this->getImage($imgURLs);
        $contentData = str_replace($imgURLs, $replaceUrl, $contentData);

        //xóa content rác
        $contentData = preg_replace('#srcset="(.*?)"#', '', $contentData);
        $contentData = preg_replace('#sizes="(.*?)"#', '', $contentData);
        $contentData = preg_replace('#aria-describedby="(.*?)"#', '', $contentData);
        $contentData = preg_replace('#class="(.*?)"#', '', $contentData);
        $contentData = preg_replace('#id="(.*?)"#', '', $contentData);
        $contentData = preg_replace('#<div\b[^>]+\bclass\s*=\s*[\'\"]fb-comments[\'\"][^>]*>([\s\S]*?)</div>#', '', $contentData);
        $contentData = preg_replace('#<style>([\s\S]*?)</style>#', '', $contentData);

        return $contentData;
    }

    /**
     * get post content from gamek.vn
     * @return bool|string|string[]|null
     */
    private function gamek() {
        if (!$content = $this->dom->find('div[class=rightdetail_content]', 0)) {
            return false;
        }

        $contentData = $content->innertext;

        //get the image url
        $imgURLs = [];
        foreach($content->find('img') as $element) {
            $imgURLs[] = $element->src;
        }
        $replaceUrl = $this->getImage($imgURLs);
        $contentData = str_replace($imgURLs, $replaceUrl, $contentData);

        //xóa content rác
        $contentData = preg_replace('#<div\s+class="VCSortableInPreviewMode\s+link-content-footer\s+IMSCurrentEditorEditObject">(.*?)</div>#', '', $contentData);
        $contentData = preg_replace('#<a\b[^>]+\bclass\s*=\s*[\'\"]link-source-full[\'\"][^>]*>([\s\S]*?)</a>#', '', $contentData);
        $contentData = preg_replace('#<div\b[^>]+\bclass\s*=\s*[\'\"]VCSortableInPreviewMode\s+link-content-footer\s+IMSCurrentEditorEditObject[\'\"][^>]*>([\s\S]*?)</div>#', '', $contentData);
        $contentData = preg_replace('#<div\b[^>]+\bclass\s*=\s*[\'\"]link-source-wrapper\s+is-web\s+clearfix\s+mb20[\'\"][^>]*>([\s\S]*?)</div>#', '', $contentData);
        $contentData = preg_replace('#<script>([\s\S]*?)</script>#', '', $contentData);
        $contentData = preg_replace('#<style>([\s\S]*?)</style>#', '', $contentData);
        $contentData = preg_replace('#data-original="(.*?)"#', '', $contentData);
        $contentData = preg_replace('#style="(.*?)"#', '', $contentData);
        $contentData = preg_replace('#type="photo"#', '', $contentData);
        $contentData = preg_replace('#photoid="(.*?)"#', '', $contentData);
        $contentData = preg_replace('#width#', '', $contentData);
        $contentData = preg_replace('#w="(.*?)"#', '', $contentData);
        $contentData = preg_replace('#h="(.*?)"#', '', $contentData);
        $contentData = preg_replace('#height#', '', $contentData);
        $contentData = preg_replace('#rel="lightbox"#', '', $contentData);
        $contentData = preg_replace('#=""#', '', $contentData);
        $contentData = preg_replace('#<div\s+id="([\s\S]*?)"></div>#', '', $contentData);
        $contentData = preg_replace('#<div\s+class="arrow-down"></div>#', '', $contentData);

        return $contentData;
    }

    /**
     * get post content from infogame
     * @return bool
     */
    private function infogame() {
        if (!$content = $this->dom->find('div[class=noidung]', 0)) {
            return false;
        }

        $contentData = $content->innertext;

        //get the image url
        $imgURLs = [];
        foreach($content->find('img') as $element) {
            $imgURLs[] = $element->src;
        }
        $replaceUrl = $this->getImage($imgURLs);
        $contentData = str_replace($imgURLs, $replaceUrl, $contentData);

        return $contentData;
    }

    /**
     * get post content from techrum
     * @return bool|string|string[]
     * @throws \ReflectionException
     */
    private function techrum() {
        if (!$content = $this->dom->find('article', 0)) {
            return false;
        }

        $contentData = $content->innertext;

        //get the image url
        $imgURLs = [];
        foreach($content->find('img') as $element) {
            $imgURLs[] = $element->src;
        }
        $replaceUrl = $this->getImage($imgURLs);
        $contentData = str_replace($imgURLs, $replaceUrl, $contentData);

        return $contentData;
    }

    /**
     * get post content from xemgame
     * @return bool|string|string[]
     * @throws \ReflectionException
     */
    private function xemgame() {
        if (!$content = $this->dom->find('div[class=rightdetail_content]', 0)) {
            return false;
        }
        //get the image url
        $imgURLs = [];
        foreach($content->find('img') as $element) {
            $imgURLs[] = $element->src;
        }
        $replaceUrl = $this->getImage($imgURLs);
        $contentData = $content->innertext;

        $contentData = str_replace($imgURLs, $replaceUrl, $contentData);

        return $contentData;
    }

    /**
     * get post content from tinanime
     * @return bool|string|string[]
     * @throws \ReflectionException
     */
    private function tinanime() {
        if (!$content = $this->dom->find('div[class=news-content]', 0)) {
            return false;
        }
        //get the image url
        $imgURLs = [];
        foreach($content->find('img') as $element) {
            $imgURLs[] = $element->src;
        }
        $replaceUrl = $this->getImage($imgURLs);
        $contentData = $content->innertext;

        $contentData = str_replace($imgURLs, $replaceUrl, $contentData);

        return $contentData;
    }

    /**
     * download all image from post to server
     * @param array $urls
     * @return array
     * @throws \ReflectionException
     */
    public function getImage(Array $urls) {
        helper('text');
        $attachModel = new AttachModel();
        $configs = config("Acp");

        $sub_folder = "attach/".date('Y/m/d');
        $imagePath = $configs->uploadFolder.$sub_folder;

        //check path
        if ( !is_dir($imagePath) ) {
            mkdir($imagePath, 0755, true);
        }

        $imgResult = [];
        foreach ($urls as $item) {
            if ( preg_match('/(\.jpg|\.png|\.jpeg|\.gif)$/', $item) ) {
                $info = pathinfo($item);
                $dest_file_name = 'embergame-'.random_string('alnum', 25).".{$info['extension']}";
                copy($item, $imagePath.'/'.$dest_file_name);
                if ( file_exists($imagePath.'/'.$dest_file_name) ){ //make sure the image exist
                    //create thumb
                    $imgThumb = [
                        'file_name' => $dest_file_name,
                        'original_image' => $imagePath.'/'.$dest_file_name,
                        'path' => $imagePath."/thumb"
                    ];
                    create_thumb($imgThumb);

                    //insert data image
                    $insertData = [
                        'user_id' => $this->user->id,
                        'user_type' => UserModel::class,
                        'file_name' => $dest_file_name,
                        'file_title' => $dest_file_name
                    ];
                    if ( !$attachModel->insert($insertData) ) {
                        //insert fail
                        $this->delete_image($dest_file_name, $sub_folder);
                    } else $imgResult[] = base_url('uploads/'.$sub_folder.'/'.$dest_file_name);
                }
            }
        }
        return $imgResult;
    }
}