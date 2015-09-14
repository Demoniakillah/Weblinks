<?php

namespace WebLinks\DAO;

use WebLinks\Domain\Link;

class LinkDAO extends DAO 
{
    /**
     * Returns a list of all links, sorted by id.
     *
     * @return array A list of all links.
     */
    public function findAll() {
        $sql = "select * from t_link order by link_id desc";
        $result = $this->getDb()->fetchAll($sql);
        
        // Convert query result to an array of domain objects
        $entities = array();
        foreach ($result as $row) {
            $id = $row['link_id'];
            $entities[$id] = $this->buildDomainObject($row);
            $userData = $this->getDb()->fetchAll("select * from t_user where user_id=?",array($row['user_id']));
            $user = new \WebLinks\Domain\User($userData[0]);
            $entities[$id]->setUser($user);
        }
        return $entities;
    }
    
    public function find($id) {
        $sql = "select * from t_link where link_id=?";
        $result = $this->getDb()->fetchAll($sql,array($id));
        
        // Convert query result to an array of domain objects
        $entities = array();
        foreach ($result as $row) {
            $id = $row['link_id'];
            $entities[$id] = $this->buildDomainObject($row);
            $userData = $this->getDb()->fetchAll("select * from t_user where user_id=?",array($row['user_id']));
            $user = new \WebLinks\Domain\User($userData[0]);
            $entities[$id]->setUser($user);
        }
        return $entities[$id];
    }
    
    /**
     * Save or Update a link
     * 
     * @param Link $link
     * @param type $userId
     */
    public function save(Link $link, $userId=null){
        $linkData = array(
            'link_title'=>$link->getTitle(),
            'link_url'=>$link->getUrl(),
            'user_id'=>$userId
        );
        if($link->getId()){
            //Update if already exist
            $this->getDb()->update('t_link', array('link_title'=>$link->getTitle(),'link_url'=>$link->getUrl()), array('link_id'=>$link->getId()));
            $state = array ('type'=>'message','message'=>' The link was successfully updated. ');
        }
        else{
            // Create new link
            if($this->exists($linkData)){
            $state = array ('type'=>'info','message'=>'Sorry, a link with a same title already exists. Try another');
            }
            else{
                $this->getDb()->insert('t_link', $linkData);
                $id = $this->getDb()->lastInsertId();
                $link->setId($id);
                $state = array ('type'=>'message','message'=>' The link was successfully submitted. ');
            }
        }
        return $state;
    }
    
    //Delete link
    public function delete($id){
        $this->getDb()->delete('t_link', array('link_id'=>$id));
    }
    
    /*
     * Look if link already exists
     */
    protected function exists(Array $linkData){
        $sql = "select link_title from t_link";
        $result = $this->getDb()->fetchAll($sql);
        foreach ($result as $link){
            if($link['link_title'] == $linkData['link_title']){
                return true;
            }
        }
    }
    
    public function deleteAllByUser($id){
        $this->getDb()->delete('t_link', array('user_id'=>$id));
    }
    

    /**
     * Creates an Link object based on a DB row.
     *
     * @param array $row The DB row containing Link data.
     * @return \WebLinks\Domain\Link
     */
    protected function buildDomainObject($row) {
        $link = new Link();
        $link->setId($row['link_id']);
        $link->setUrl($row['link_url']);
        $link->setTitle($row['link_title']);
        return $link;
    }
}
