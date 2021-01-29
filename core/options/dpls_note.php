<div class="wrap">
    <h1>Scraper Configuration</h1>

    <form method="post" action="options.php" novalidate="novalidate">
        <table class="form-table">

            <tbody>
            <tr>
                <th scope="row"><label for="base">Scraper Run Once</label></th>
                <td><input name="scraper_run" type="number" id="blogname" value="1" class="regular-text">/days</td>
            </tr>

            <tr>
                <th scope="row"><label for="base">Scraping in Site Name</label></th>
                <td><input name="scraper_site" type="text" id="blogname" value="https://www.poznavach.com/" class="regular-text"></td>
            </tr>

            <tr>
                <th scope="row"><label for="base">Use Advance Caching System</label></th>
                <td><input checked name="scraper_caching" type="checkbox" id="blogname" class="regular-text"></td>
            </tr>

            <tr>
                <th scope="row"><label for="base">Use File Stroage System</label></th>
                <td><input checked name="scraper_file" type="checkbox" id="blogname" class="regular-text"></td>
            </tr>




            </tbody>
        </table>


        <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary"
                                 value="Save Changes"></p></form>

</div>