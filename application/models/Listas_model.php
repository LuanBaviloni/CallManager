<?php

class Listas_model extends CI_Model {

    function getAll($ID = NULL) {
        $this->db->select('*');
        $this->db->from('listas');
        if ($ID != NULL) {
            $this->db->where('login_id', $ID);
        }

        $query = $this->db->get();
        return $query->result();
    }

    function insert_lista($batch) {
        if ($batch != NULL) {
            $this->db->insert_batch('pessoas', $batch);
        }
    }
    
    function getAlarms($ID = NULL)
    {
        $this->db->select('*');
        $this->db->from('pessoas');
        $this->db->join('listas', 'pessoas.lista_id=listas.lista_id');
        
        if ($ID != NULL) {
            $this->db->where('login_id', $ID);
        }
        
        $this->db->where('NOW() > ','ADDDATE(pessoa_alerta, INTERVAL -10 MINUTE)', FALSE);
        $this->db->where('NOW() < ','pessoa_alerta', FALSE);
        $query = $this->db->get();
        
        $rows = $query->result();
        foreach($rows as $i => $row) {
            $rows[$i]->pessoa_alerta = date("d/m/Y - H:i",strtotime($row->pessoa_alerta));
            $rows[$i]->pessoa_telclear = preg_replace("/[^0-9]/", "", $row->pessoa_telmain);
            if ($rows[$i]->pessoa_responsavel == "") {
                $rows[$i]->pessoa_responsavel = '------------------';
            }
        }
        $jsonEncode  =  json_encode($rows);
        return $jsonEncode;
    }

}
