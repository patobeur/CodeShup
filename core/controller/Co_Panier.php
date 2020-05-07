<?php
// ConTroller > Profil

if (!empty($_SESSION['profil']))
{
    $replace_in_vue = [
        'titre1'         => 'Panier',
    ];


	if (class_exists('Db')) {
        $Panier  = new Boutique();

        function articlesEnSession($Panier)
        {
            if (isset($_SESSION['panier']))
            {   
                $wherein_arr = [];
                foreach ($_SESSION['panier'] as $value)
                {
                    $wherein_arr[] = $value['article'];
                }
                $wherein = "('".implode("','", $wherein_arr)."')";
                $mesarticlesensession = $Panier->get_panier_session($wherein);
                if (!empty($mesarticlesensession))
                {   
                    $totalprice = 0;
                    $lignes = "";
                    $nbunites = 0;
                    foreach ($mesarticlesensession as $key => $value)
                    {
                        $mesarticlesensession[$key]->nb = $_SESSION['panier'][$value->product_id]['nb'];
                        $dispo = ($mesarticlesensession[$key]->stock >= $mesarticlesensession[$key]->nb) ? 'Disponible' : 'Epuisé';
                        $prixtotalarticle = ($mesarticlesensession[$key]->price * $mesarticlesensession[$key]->nb);
                        $totalprice = $totalprice + $prixtotalarticle;
                        $nbunites = $nbunites + $mesarticlesensession[$key]->nb;
                        $lignes .='
                        <tr>
                            <td>'.($key + 1).'</td>
                            <td>'.$mesarticlesensession[$key]->name.'</td>
                            <td>'.$dispo.'</td>
                            <td>'.$mesarticlesensession[$key]->nb.'</td>
                            <td>'.$mesarticlesensession[$key]->price.' €</td>
                            <td>'.$prixtotalarticle.' €</td>
                            <td> + / - / delete </td>
                        </tr>';
                    }
                    $lignes .='
                    <tr>
                        <td colspan="4"></td>
                        <td>'.$key.' articles </td>
                        <td colspan="2"></td>
                    </tr>
                        <td colspan="4"></td>
                        <td>'.$nbunites.' unités au total </td>
                        <td colspan="2"></td>
                    </tr>
                    <tr>
                        <td colspan="5"></td>
                        <td colspan="2">'.$totalprice.' € HT au total</td>
                    </tr>';
                }
            return [$mesarticlesensession, $lignes];
            }
        }

        $retours = articlesEnSession($Panier);
        $mesarticlesensession = $retours[0];
        $lignes = $retours[1];
        $replace_in_vue = [
            'titre1'         => 'Panier',
            'TITRE'             => 'ok Panier',
            'ARTICLESPANIER'    => $lignes
        ];
    }

        


    Page::set_replace_in_vue($replace_in_vue);
}
?>