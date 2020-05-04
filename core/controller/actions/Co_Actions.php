<?php


                    $page_cible = 'actions';
                    // print_airB($replace_in_vue[$page_cible],'replace_in_vue');
                    // 1
                    // echo 'controller action';
                    $lettre = "";
                    $idd = null;
                    $limite = '';//" LIMIT 10";
                    $req = "SELECT
                    product_id,cat_id,vendor_id
                    FROM z_product
                    ORDER BY product_id DESC".$limite;
                    $laliste_items = $this->_Dbadmin->actionsAdminFinal($idd,$req);
                    $intitules_array = ['product_id','vendor_id','cat_id'];

                    $product_id = ""; $cat_id = ""; $vendor_id = "";  $num = 0;
                    foreach($laliste_items as $key => $value)
                    {
                        $virg = ($num==0) ? '' : ",";
                        if ( !empty($value->product_id) && !empty($value->product_id) && !empty($value->vendor_id)) {
                            $product_id .= $virg.''.$value->product_id;
                            $cat_id     .= $virg.''.$value->cat_id;
                            $vendor_id  .= $virg.''.$value->vendor_id;
                        $num++;
                        } 
                    }
                    $plus1 = PHP_EOL.'<br>product_id : ('.$product_id.')<br/>';
                    $plus1 .= 'vendor_id : ('.$vendor_id.')<br/>';
                    $plus1 .= 'cat_id : ('.$cat_id.')<br/><br/>';

                    $data_recuphtml = ResponseToHtml($intitules_array,$laliste_items);

                    $replace_in_vue[$page_cible]['intitules1'] = $data_recuphtml['intitules'];
                    $replace_in_vue[$page_cible]['titre1'] = 'liste des paniers (création d\'array pour les futures WHERE IN';   
                    $replace_in_vue[$page_cible]['test1'] =  (!empty($data_recuphtml['items']) ? $data_recuphtml['items'] : null);
                    $replace_in_vue[$page_cible]['requete1'] = (!empty($req) ? $req.$plus1 : 'votre demande est vide !');

?>