<page  backright="25%" backtop="25%" >
    <page_header>
       Je suis le header dans la page includes/App/Views/pdf/default
    </page_header>
    <page_footer>
        ...
    </page_footer>
    <nobreak>
    <table cellspacing="10" style="font-size: 10px">
        <thead>
            <tr>
                <th>id</th>
                <th>firstname</th>
                <th>lastname</th>
                
                <th>email</th>
                
                <th>city</th>
                <th>cp</th>
                <th>country</th>
                <th>newsletter</th>
                <th>club</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($datas as $data) : ?>

            <tr>
                <td><?= $data->id;?></td>
                <td><?= $data->firstname;?></td>
                <td><?= $data->lastname;?></td>
                
                <td><?= $data->email;?></td>
                
                <td><?= $data->city;?></td>
                <td><?= $data->cp;?></td>
                <td><?= $data->country;?></td>
                <td><?= $data->newsletter;?></td>
                <td><?= $data->club;?></td>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</nobreak>
</page>
