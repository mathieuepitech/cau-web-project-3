</div>
</main>

<div id="modal" class="md-modal">
    <div class="md-content">

        <h3 class="md-header">Add contact</h3>

        <div class="md-container row">
            <form class="col s12">
                <div class="row">
                    <div class="input-field col s6">
                        <input id="first_name" type="text" class="validate" required>
                        <label for="first_name">First Name*</label>
                    </div>
                    <div class="input-field col s6">
                        <input id="last_name" type="text" class="validate" required>
                        <label for="last_name">Last Name*</label>
                    </div>
                    <div class="input-field col s6">
                        <input id="surname" type="text" class="validate">
                        <label for="surname">Surname</label>
                    </div>
                    <div class="input-field col s6">
                        <input id="email" type="email" class="validate">
                        <label for="email">Email</label>
                    </div>
                    <div class="input-field col s6">
                        <input id="address" type="text" class="validate">
                        <label for="address">Address</label>
                    </div>
                    <div class="input-field col s6">
                        <input id="phone_number" type="tel" class="validate">
                        <label for="phone_number">Phone Number</label>
                    </div>
                    <div class="input-field col s6">
                        <input id="birthday" type="date" class="validate">
                        <label for="birthday">Birthday</label>
                    </div>
                    <div class="col s12 mandatory">* fields mandatory</div>
                </div>
            </form>
        </div>

        <div class="md-footer">
            <a class="waves-effect waves-green btn-flat" id="md-validate">Validate</a>
            <a class="waves-effect waves-red btn-flat" id="md-cancel">Cancel</a>
        </div>

    </div>
</div>
<div class="md-overlay"></div>

<footer class="page-footer blue">
    <div class="footer-copyright">
        <div class="container">
            Made by <a class="blue-text text-lighten-3" target="_blank" href="https://www.sanchez-mathieu.fr">Mathieu
                Sanchez</a>
        </div>
    </div>
</footer>

<script type="text/javascript" src="/js/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="/js/materialize.min.js"></script>

<script async type="text/javascript"
        src="/js/javascript.js?v=<?= CAUProject3Contact\Config::SITE_JS_VERSION ?>"></script>

<link href="/css/style.css?v=<?= CAUProject3Contact\Config::SITE_CSS_VERSION ?>" rel="stylesheet">

</body>
</html>
