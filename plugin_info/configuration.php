<?php
/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */

require_once __DIR__ . '/../../../core/php/core.inc.php';
include_file('core', 'authentification', 'php');
if (!isConnect()) {
    include_file('desktop', '404', 'php');
    die();
}
?>
<head>
    <style>
        #images-box {
            /* La largeur totale du bloc conteneur, essentiellement pour le centrage */
            width: 100%;
            margin: 0px auto;
            /*position: relative;*/
            top: 70px;
        }

        .image-lightbox img {
            /* Chaque image hérite ses dimensions de son parent */
            width: inherit;
            height: inherit;
        }

        .holder {
            /* Dimension des images, vous pouvez les modifier */
            width: 128px;
            height: 128px;
            /* Flottement à gauche, donc l'ensemble est aligné à droite */
            float: left;
            margin: 0 20px 0 0;
        }

        .holder.little {
            width: 42px;
            height: 42px;
        }

        .image-lightbox {
            /* Les dimensions héritent de .holder */
            width: inherit;
            height: inherit;
            /*padding: 10px;*/
            /* Ombrage des blocs */
            box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
            background: rgba(0,0,0,0);
            border-radius: 5px;
            /* Position absolue pour permettre de zoomer ultérieurement */
            /*position: absolute;*/
            top: 0;
            font-family: Arial, sans-serif;
            /* Transitions pour rendre l'ensemble visuellement abouti */
            -webkit-transition: all ease-in 0.5s;
            -moz-transition: all ease-in 0.5s;
            -ms-transition: all ease-in 0.5s;
            -o-transition: all ease-in 0.5s;
        }

        .image-lightbox span {
            display: none;
        }

        .image-lightbox .expand {
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
            z-index: 4000;
            background: rgba(0,0,0,0); /* Fixe un bogue d'IE */
        }

        .image-lightbox .close {
            position: absolute;
            width: 20px; height: 20px;
            right: 20px; top: 20px;
        }

        .image-lightbox .close a {
            height: auto; width: auto;
            padding: 5px 10px;
            color: #fff;
            text-decoration: none;
            background: #22272c;
            box-shadow: inset 0px 24px 20px -15px rgba(255, 255, 255, 0.1),
                inset 0px 0px 10px rgba(0,0,0,0.4),
                0px 0px 30px rgba(255,255,255,0.4);
            border-radius: 5px;
            font-weight: bold;
            float: right;
        }

        .close a:hover {
            box-shadow: inset 0px -24px 20px -15px rgba(255, 255, 255, 0.01),
                inset 0px 0px 10px rgba(0,0,0,0.4),
                0px 0px 20px rgba(255,255,255,0.4);
        }

        /*div[id^=image]:target {
                width: 450px;
                height: 300px;
                z-index: 5000;
                top: 50px;
                left: 200px;
        }
        div[id^=image]:target .close {
                display: block;
        }
        
        div[id^=image]:target .expand {
                display: none;
        }*/
        <?php
        $dir          = 'plugins/Opening/core/template/dashboard/images/';
        $file_display = array('jpg', 'jpeg', 'png', 'gif');

        if (file_exists($dir) === false) {
            echo 'Directory "', $dir, '" not found!';
        } else {
            $dir_contents = scandir($dir);
            $i            = 0;
            foreach ($dir_contents as $file) {
                $file_type = strtolower(end(explode('.', $file)));
                if (in_array($file_type, $file_display)) {
                    $name  = basename($file);
                    $width = $i % 3 * 290;
                    $i++;
                    echo "div#image-$name { left: $width px; }";
                }
            }
        }
        ?>

    </style>
</head>
<form class="form-horizontal">
    <div class="panel panel-info" style="height: 100%;">
        <div class="panel-heading" role="tab">
            <h4 class="panel-title">
                Plugin Opening
            </h4>
        </div>
        <div class="form-group">
            <br>
            <label class="col-sm-2 control-label">{{Configuration}} :</label>
            <div class="col-lg-4">
                <a class="btn btn-info" href="/index.php?v=d&m=Opening&p=Opening"> {{Accès à la configuration}}</a>
            </div>
            <br>
        </div>
        <div class="form-group">
            <br>
            <label class="col-sm-2 control-label">{{Version}} :</label>
            <div class="col-lg-8" style="margin-top:9px">1.0<br><br>
            </div>
            <br>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">{{Présentation}} :</label>
            <div class="col-lg-8" style="margin-top:9px">
                {{Plugin pour créer un ouvrant paramètrable à souhait et intégrant plusieurs calques et capteurs en un widget}}.<br><br>
                {{Il est gratuit pour que chacun puisse en profiter simplement.}}<br><br>
                {{Si vous souhaitez tout de même faire un don aux développeurs du plugin, utilisez les liens suivants.}}<br><br>
                {{Si possible, divisez-le par le nombre de développeur ;)}}<br><br>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">{{Dons aux Développeurs}} :</label>
            <div class="col-lg-8" style="margin-top:9px">
                <li>{{Code et Documentation}} : Virux   >
                    <a class="btn-link" id="bt_paypal" href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=HM5MQ7APM44PY&lc=FR&item_name=Plugin%20Jeedom%20Opening&currency_code=EUR&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted" target="_new" >
                        <img src="https://www.paypalobjects.com/fr_FR/FR/i/btn/btn_donate_LG.gif" alt="{{Faire un don via Paypal au développeur Virux}}">
                    </a>
                </li><br>
                <li>{{Code et Graphisme}} : Cyrilphoenix   >
                    <a class="btn-link" id="bt_paypal" href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=UXRRCYJZTWTMN&lc=FR&item_name=Plugin%20Jeedom%20Opening&item_number=Plugin%20Jeedom%20Opening&currency_code=EUR&bn=PP%2dDonationsBF%3abtn_donate_LG%2egif%3aNonHosted" target="_new" >
                        <img src="https://www.paypalobjects.com/fr_FR/FR/i/btn/btn_donate_LG.gif" alt="{{Faire un don via Paypal au développeur Cyrilphoenix}}">
                    </a>
                </li><br>
                <li>{{Administration et Graphisme}} : Slobberbone   >
                    <a class="btn-link" id="bt_paypal" href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=PH295BMQ33EFN&lc=FR&item_name=Plugin%20Jeedom%20pulseaudio&currency_code=EUR" target="_new" >
                        <img src="https://www.paypalobjects.com/fr_FR/FR/i/btn/btn_donate_LG.gif" alt="{{Faire un don via Paypal au développeur Slobberbone}}">
                    </a>
                </li>
            </div>	
        </div>
    </div>
    <div class="panel panel-info" style="height: 100%;">
        <div class="panel-heading" role="tab">
            <h4 class="panel-title">
                {{Galerie des portes et fenêtres}}
            </h4>
        </div>
        <div class="form-group">
            <br>
            <label class="col-sm-1 control-label"></label>
            <div class="col-lg-10">
                <div id="images-box">
<?php
$dir          = 'plugins/Opening/core/template/dashboard/images/';
$file_display = array('jpg', 'jpeg', 'png', 'gif');

if (file_exists($dir) === false) {
    echo 'Directory "', $dir, '" not found!';
} else {
    $dir_contents = scandir($dir);

    foreach ($dir_contents as $file) {
        $file_type = strtolower(end(explode('.', $file)));
        if (in_array($file_type, $file_display)) {
            $name = basename($file);
            if (strpos($name, 'Opening') !== false) {
                echo "<div class='holder'>";
                echo "    <div id='image-$name' class='image-lightbox'>";
                //echo "        <span class='close'><a href='#'>X</a></span>";
                echo "        <img src='$dir$file' alt='$name' title='$name'>";
                //echo "        <a class='expand' href='#image-$name'></a>";
                echo "    </div>";
                echo "</div>";
            }
        }
    }
}
?>
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-info" style="height: 100%;">
        <div class="panel-heading" role="tab">
            <h4 class="panel-title">
                {{Galerie des volets}}
            </h4>
        </div>
        <div class="form-group">
            <br>
            <label class="col-sm-1 control-label"></label>
            <div class="col-lg-10">
                <div id="images-box">
<?php
$dir          = 'plugins/Opening/core/template/dashboard/images/';
$file_display = array('jpg', 'jpeg', 'png', 'gif');

if (file_exists($dir) == false) {
    echo 'Directory "', $dir, '" not found!';
} else {
    $dir_contents = scandir($dir);

    foreach ($dir_contents as $file) {
        $file_type = strtolower(end(explode('.', $file)));
        if (in_array($file_type, $file_display)) {
            $name = basename($file);
            if (strpos($name, 'Store') !== false) {
                echo "<div class='holder'>";
                echo "    <div id='image-$name' class='image-lightbox'>";
                //echo "        <span class='close'><a href='#'>X</a></span>";
                echo "        <img src='$dir$file' alt='$name' title='$name'>";
                //echo "        <a class='expand' href='#image-$name'></a>";
                echo "    </div>";
                echo "</div>";
            }
        }
    }
}
?>
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-info" style="height: 100%;">
        <div class="panel-heading" role="tab">
            <h4 class="panel-title">
                {{Galerie des paysages}}
            </h4>
        </div>
        <div class="form-group">
            <br>
            <label class="col-sm-1 control-label"></label>
            <div class="col-lg-10">
                <div id="images-box">
<?php
$dir          = 'plugins/Opening/core/template/dashboard/images/';
$file_display = array('jpg', 'jpeg', 'png', 'gif');

if (file_exists($dir) === false) {
    echo 'Directory "', $dir, '" not found!';
} else {
    $dir_contents = scandir($dir);

    foreach ($dir_contents as $file) {
        $file_type = strtolower(end(explode('.', $file)));
        if (in_array($file_type, $file_display)) {
            $name = basename($file);
            if (strpos($name, 'Back') !== false) {
                echo "<div class='holder'>";
                echo "    <div id='image-$name' class='image-lightbox'>";
                //echo "        <span class='close'><a href='#'>X</a></span>";
                echo "        <img src='$dir$file' alt='$name' title='$name'>";
                //echo "        <a class='expand' href='#image-$name'></a>";
                echo "    </div>";
                echo "</div>";
            }
        }
    }
}
?>
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-info" style="height: 100%;">
        <div class="panel-heading" role="tab">
            <h4 class="panel-title">
                {{Galerie de l'indicateur d'état d'alarme}}
            </h4>
        </div>
        <div class="form-group">
            <br>
            <label class="col-sm-1 control-label"></label>
            <div class="col-lg-10">
                <div id="images-box">
                    <?php
                    $dir          = 'plugins/Opening/core/template/dashboard/images/';
                    $file_display = array('jpg', 'jpeg', 'png', 'gif');

                    if (file_exists($dir) === false) {
                        echo 'Directory "', $dir, '" not found!';
                    } else {
                        $dir_contents = scandir($dir);

                        foreach ($dir_contents as $file) {
                            $file_type = strtolower(end(explode('.', $file)));
                            if (in_array($file_type, $file_display)) {
                                $name = basename($file);
                                if (strpos($name, 'Alarm') !== false) {
                                    echo "<div class='holder little'>";
                                    echo "    <div id='image-$name' class='image-lightbox'>";
                                    //echo "        <span class='close'><a href='#'>X</a></span>";
                                    echo "        <img src='$dir$file' alt='$name' title='$name'>";
                                    //echo "        <a class='expand' href='#image-$name'></a>";
                                    echo "    </div>";
                                    echo "</div>";
                                }
                            }
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-info" style="height: 100%;">
        <div class="panel-heading" role="tab">
            <h4 class="panel-title">
                {{Galerie de l'indicateur de batterie faible}}
            </h4>
        </div>
        <div class="form-group">
            <br>
            <label class="col-sm-1 control-label"></label>
            <div class="col-lg-10">
                <div id="images-box">
                    <?php
                    $dir          = 'plugins/Opening/core/template/dashboard/images/';
                    $file_display = array('jpg', 'jpeg', 'png', 'gif');

                    if (file_exists($dir) === false) {
                        echo 'Directory "', $dir, '" not found!';
                    } else {
                        $dir_contents = scandir($dir);

                        foreach ($dir_contents as $file) {
                            $file_type = strtolower(end(explode('.', $file)));
                            if (in_array($file_type, $file_display)) {
                                $name = basename($file);
                                if (strpos($name, 'Bat') !== false) {
                                    echo "<div class='holder little'>";
                                    echo "    <div id='image-$name' class='image-lightbox'>";
                                    //echo "        <span class='close'><a href='#'>X</a></span>";
                                    echo "        <img src='$dir$file' alt='$name' title='$name'>";
                                    //echo "        <a class='expand' href='#image-$name'></a>";
                                    echo "    </div>";
                                    echo "</div>";
                                }
                            }
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-info" style="height: 100%;">
        <div class="panel-heading" role="tab">
            <h4 class="panel-title">
                {{Galerie de l'indicateur de verrouillage}}
            </h4>
        </div>
        <div class="form-group">
            <br>
            <label class="col-sm-1 control-label"></label>
            <div class="col-lg-10">
                <div id="images-box">
                    <?php
                    $dir          = 'plugins/Opening/core/template/dashboard/images/';
                    $file_display = array('jpg', 'jpeg', 'png', 'gif');

                    if (file_exists($dir) == false) {
                        echo 'Directory "', $dir, '" not found!';
                    } else {
                        $dir_contents = scandir($dir);

                        foreach ($dir_contents as $file) {
                            $file_type = strtolower(end(explode('.', $file)));
                            if (in_array($file_type, $file_display)) {
                                $name = basename($file);
                                if (strpos($name, 'Lock') !== false) {
                                    echo "<div class='holder little'>";
                                    echo "    <div id='image-$name' class='image-lightbox'>";
                                    //echo "        <span class='close'><a href='#'>X</a></span>";
                                    echo "        <img src='$dir$file' alt='$name' title='$name'>";
                                    //echo "        <a class='expand' href='#image-$name'></a>";
                                    echo "    </div>";
                                    echo "</div>";
                                }
                            }
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-info" style="height: 100%;">
        <div class="panel-heading" role="tab">
            <h4 class="panel-title">
                {{Galerie de l'indicateur de présence ou de mouvement}}
            </h4>
        </div>
        <div class="form-group">
            <br>
            <label class="col-sm-1 control-label"></label>
            <div class="col-lg-10">
                <div id="images-box">
                    <?php
                    $dir          = 'plugins/Opening/core/template/dashboard/images/';
                    $file_display = array('jpg', 'jpeg', 'png', 'gif');

                    if (file_exists($dir) === false) {
                        echo 'Directory "', $dir, '" not found!';
                    } else {
                        $dir_contents = scandir($dir);

                        foreach ($dir_contents as $file) {
                            $file_type = strtolower(end(explode('.', $file)));
                            if (in_array($file_type, $file_display)) {
                                $name = basename($file);
                                if (strpos($name, 'Motion') !== false) {
                                    echo "<div class='holder little'>";
                                    echo "    <div id='image-$name' class='image-lightbox'>";
                                    //echo "        <span class='close'><a href='#'>X</a></span>";
                                    echo "        <img src='$dir$file' alt='$name' title='$name'>";
                                    //echo "        <a class='expand' href='#image-$name'></a>";
                                    echo "    </div>";
                                    echo "</div>";
                                }
                            }
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-info" style="height: 100%;">
        <div class="panel-heading" role="tab">
            <h4 class="panel-title">
                {{Galerie de l'indicateur météo}}
            </h4>
        </div>
        <div class="form-group">
            <br>
            <label class="col-sm-1 control-label"></label>
            <div class="col-lg-10">
                <div id="images-box">
                    <?php
                    $dir          = 'plugins/Opening/core/template/dashboard/images/';
                    $file_display = array('jpg', 'jpeg', 'png', 'gif');

                    if (file_exists($dir) === false) {
                        echo 'Directory "', $dir, '" not found!';
                    } else {
                        $dir_contents = scandir($dir);

                        foreach ($dir_contents as $file) {
                            $file_type = strtolower(end(explode('.', $file)));
                            if (in_array($file_type, $file_display)) {
                                $name = basename($file);
                                if (strpos($name, 'Weather') !== false) {
                                    echo "<div class='holder'>";
                                    echo "    <div id='image-$name' class='image-lightbox'>";
                                    //echo "        <span class='close'><a href='#'>X</a></span>";
                                    echo "        <img src='$dir$file' alt='$name' title='$name'>";
                                    //echo "        <a class='expand' href='#image-$name'></a>";
                                    echo "    </div>";
                                    echo "</div>";
                                }
                            }
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</form>
