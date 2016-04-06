<?php

class SiteModel {

    public $db;
    public $registry;

    public function __construct($registry = null) {
        //conexao com banco
        if ($registry != null) {
            $this->registry = $registry;
            $this->db = $this->registry->get('db');
        } else {
            $this->registry = Registry::getInstance();
            $this->registry->set('db', new DB);
            $this->db = $this->registry->get('db');
        }
        $this->db->find(array("site"));
        //$this->site_data = $this->db->data;
        $this->site_tema = $this->db->data->site_tema;
        $this->site_template = $this->db->data->site_template;
        $this->site_template_skin = $this->db->data->site_template_skin;
        $this->site_color = $this->db->data->site_color;
        $this->site_title = $this->db->data->site_title;
        $this->site_slogan = $this->db->data->site_slogan;
        $this->site_about = $this->db->data->site_about;
        $this->site_meta_desc = $this->db->data->site_meta_desc;
        $this->site_meta_autor = $this->db->data->site_meta_autor;
        $this->site_meta_title = $this->db->data->site_meta_title;
        $this->site_meta_keywords = $this->db->data->site_meta_keywords;
        $this->site_logo = $this->db->data->site_logo;
        $this->site_logo_x = $this->db->data->site_logo_x;
        $this->site_logo_y = $this->db->data->site_logo_y;
        $this->site_google_analytics = $this->db->data->site_google_analytics;
        $this->site_template_box = $this->db->data->site_template_box;
        $this->site_template_dir = $this->db->data->site_template;
    }

    public function getTitle() {
        return stripslashes($this->site_title);
    }

    public function getSiteAbout() {
        return stripslashes($this->site_about);
    }

    public function getMetaAuthor() {
        return $this->site_meta_autor;
    }

    public function getMetaTitle() {
        return stripslashes($this->site_meta_title);
    }

    public function getMetaDescription() {
        return stripslashes($this->site_meta_desc);
    }

    public function getMetaKeyWords() {
        return stripslashes($this->site_meta_keywords);
    }

    public function getSlogan() {
        return stripslashes($this->site_slogan);
    }

    public function getTema() {
        return $this->site_tema;
    }

    public function getTemplate() {
        return $this->site_template;
    }

    public function getTemplateSkin() {
        return $this->site_template_skin;
    }

    public function getTemplateDir() {
        return $this->site_template_dir;
    }

    public function getTemplateBox() {
        return $this->site_template_box;
    }

    public function getColor() {
        return $this->site_color;
    }

    public function getLogo() {
        return ($this->site_logo == "") ? 'main-logo.png' : $this->site_logo;
    }

    public function getLogoX() {
        return Filter::parse_int($this->site_logo_x);
    }
    public function getLogoY() {
        return Filter::parse_int($this->site_logo_y);
    }

    public function getGoogleAnalytics() {
        return Filter::trim_str($this->site_google_analytics);
    }

    //método destrutor 
    public function __destruct() {
        /*
          $this->db->destroy();
          unset($this->db);
          unset($this->registry);
         */
    }

}
