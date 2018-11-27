<?php

namespace CAUProject3Contact\Controller\Site;

use CAUProject3Contact\Controller\ControllerSite;
use CAUProject3Contact\Model\Contact;

class Index extends ControllerSite {

    /**
     * Index constructor.
     */
    public function __construct() {
        parent::__construct();

        $this->addHead( [] );

        $this->addFooter( [] );

        $contacts = Contact::getAllContact();

        $this->addData( [
            "contacts" => $contacts
        ] );
        $this->view();
    }
}

?>