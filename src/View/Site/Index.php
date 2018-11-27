<div class="section scrollspy">

    <div class="row">
        <div class="col s12 main-container">
            <table class="highlight centered">
                <thead>
                <tr>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Surname</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>Phone Number</th>
                    <th>Birthday</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ( $contacts as $contact ) {
                    echo "<tr>";
                    echo "  <td>" . $contact->getFirstName() . "</td>";
                    echo "  <td>" . $contact->getLastName() . "</td>";
                    echo "  <td>" . ( $contact->getSurname() ? $contact->getSurname() : "" ) . "</td>";
                    echo "  <td>" . ( $contact->getEmail() ? $contact->getEmail() : "" ) . "</td>";
                    echo "  <td>" . ( $contact->getAddress() ? $contact->getAddress() : "" ) . "</td>";
                    echo "  <td>" . ( $contact->getPhoneNumber() ? $contact->getPhoneNumber() : "" ) . "</td>";
                    echo "  <td>" . ( $contact->getBirthday() ? date( "Y-m-d", strtotime( $contact->getBirthday() ) ) : "" ) . "</td>";
                    echo "</tr>";
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    let contacts = <?= json_encode( $contacts ) ?>;
</script>