<?php

class IntegrationData {

    public int $id;
    public string $name;
    public bool $enabled;
    public bool $can_be_unlink;
    public bool $required;
    public int $order;

    public function __construct(object $row) {
        $this->id = $row->id;
        $this->name = $row->name;
        $this->enabled = $row->enabled;
        $this->can_be_unlink = $row->can_be_unlink;
        $this->required = $row->required;
        $this->order = $row->order;
    }

}
