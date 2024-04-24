<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ejercicio 1.2 U5 - DAW</title>
<?php    
    /**
    *
    * Estilos CSS
    *
    * Para este proyecto se han incluído los estilos CSS en el mismo fichero del script
    *
    */
?>
    <style>
        h1, h4, label, span{
            text-align: center;
            font-family: system-ui;
        }
        .flex{
            display:flex;
            justify-content:center;
        }
        section{
            width:50%;
        }
        div{
            justify-content:center;
            display:flex;
            flex-direction: column;
            width:60%;
        }
        input[type=text]{
            margin-bottom:10px;
        }
        span{
            color:red;
        }
    </style>
</head>
<body>
<?php    
    /**
    *
    * Encriptron 1.0
    *
    * Este script permite tanto encriptar como desencriptar cadenas de caracteres aplicando un salto entre 1 y 20.
    * Cuenta con dos formularios que funcionan de forma análoga ya que en ambos casos se debe indicar la cadena a encriptar / desencriptar
    * así como el salto que se le debe aplicar.
    * El formulario tiene validaciones implementadas para controlar que se haya indicado una cadena y que se haya indicado un salto
    * entre 1 y 20, mostrando un mensaje informativo en caso de no cumplir las condiciones
    */

        if(isset($_POST['enviarEncriptar']) && isset($_POST['cadenaEnc']) && isset($_POST['saltoEnc'])){
            $salto_correcto=comprobar_salto($_POST['saltoEnc']);
            if($salto_correcto==1){
                $resultado=procesar($_POST['cadenaEnc'], $_POST['saltoEnc'], "enc");
            }
        }else if(isset($_POST['enviarDesencriptar']) && isset($_POST['cadenaDes']) && isset($_POST['saltoDes'])){
            $salto_correcto=comprobar_salto($_POST['saltoDes']);
            if($salto_correcto==1){
                $resultado=procesar($_POST['cadenaDes'], $_POST['saltoDes'], "des");
            }
        }

    ?>
    <header>
        <h1>ENCRIPTRON 1.0</h1>
    </header>
    <main class="flex">
        <section>
        <h1>Encriptar</h1>
        <form class="flex" method="post" action=<?php echo $_SERVER['PHP_SELF'] ?>>
            <div>
                <label for="cadenaEnc">Cadena a encriptar</label>
                <input type="text" id="cadenaEnc" name="cadenaEnc"></br>
                <?php if(isset($_POST['enviarEncriptar']) && (!isset($_POST['cadenaEnc']) || $_POST['cadenaEnc']=="")) echo '<span>Debe indicar un texto a encriptar</span></br>';?>
                <label for="saltoEnc">Salto a aplicar</label>
                <input type="text" id="saltoEnc" name="saltoEnc"></br>
                <?php if(isset($_POST['enviarEncriptar']) && $salto_correcto==0) echo '<span>Valor de salto incorrecto</span></br>';?>
                <input type="submit" name="enviarEncriptar" value="Encriptar"></br>
                <h4 id="resultado"><?php if(isset($_POST['enviarEncriptar']) && isset($resultado) && $resultado!="") echo "Resultado ".$resultado; ?></h4>
            </div>
        </form>
        </section>
        <section>
        <h1>Desencriptar</h1>
        <form class="flex" method="post" action=<?php echo $_SERVER['PHP_SELF'] ?>>
            <div>
                <label for="cadenaDes">Cadena a desencriptar</label>
                <input type="text" id="cadenaDes" name="cadenaDes"></br>
                <?php if(isset($_POST['enviarDesencriptar']) && (!isset($_POST['cadenaDes']) || $_POST['cadenaDes']=="")) echo '<span>Debe indicar un texto a desencriptar</span></br>';?>
                <label for="saltoDes">Salto aplicado</label>
                <input type="text" id="saltoDes" name="saltoDes"></br>
                <?php if(isset($_POST['enviarDesencriptar']) && $salto_correcto==0) echo '<span>Valor de salto incorrecto</span></br>';?>
                <input type="submit" name="enviarDesencriptar" value="Desencriptar"></br>
                <h4 id="resultado"><?php if(isset($_POST['enviarDesencriptar']) && isset($resultado) && $resultado!="") echo "Resultado ".$resultado; ?></h4>
            </div>
        </form>
        </section>
    </main>
</body>


<?php    
    /**
    *
    * Función comprobar_salto
    * @author Francisco Puyol <fpuyolg@gmail.com>
    * @version 1.0
    * @internal Esta función recibe el salto en caso de que el usuario lo haya introducido y comprueba que sea 
    * un valor entero y que sea entre 1 y 20.
    *
    * Parámetro de entrada:
    * @param numeric $salto Es el salto indicado por el usuario
    * Parámetro de salida:
    * @param numeric La función devuelve 0 si el salto no cumple las condiciones (valor numérico entre 1 y 20) y 1 si las cumple
    * 
    */
function comprobar_salto($salto){
    if($salto > 0 && $salto <= 20 && is_numeric($salto)==1){
        return 1;
    }else return 0;
}

    /**
    *
    * Función procesar
    * @author Francisco Puyol <fpuyolg@gmail.com>
    * @version 1.2
    * Función que recibe una cadena, un salto y según se haya pulsado "Encriptar" o "Desencriptar", hará la operación correspondiente y 
    * mostrará el resultado
    * @internal La función aplica el salto al código ASCII de cada caracter de la cadena. Dado que se utiliza la tabla ASCII
    * Si se realiza una encriptación y el resultado es 
    * mayor que 127 empezamos no en el 0, sino a partir del código ASCII 33. Por contra, si estamos desencriptando, se le resta el salto al 
    * código ASCII del carácter, pasando del 33 no al 32, sino al 127
    * @example Al encriptar el caracter z si le aplicamos un salto 6 le corresponderá ! (ASCII 33). Por contra, al desencriptar # y aplicarle un 
    * salto de 7 le corresponderá el caracter {
    *
    * Parámetros de entrada:
    * @param string $cadena Cadena a encriptar o desencriptar (según la operación recibida)
    * @param numeric $salto Salto indicado por el usuario, validado previamente por lo que se puede aplicar directamente en la función
    * @param string $operacion Identificador del tipo de operación a realizar, "enc" para encriptar y "des" para desencriptar
    * Parámetro de salida:
    * @param string $resultado Cadena resultante de aplicarle el salto a la cadena de entrada
    * 
    */
function procesar($cadena, $salto, $operacion){
    $resultado="";
    $siguiente_char="";

    for($i=0; $i<strlen($cadena); $i++){
        if($operacion=="enc"){
            $siguiente_char=ord($cadena[$i])+$salto;  
        }else{
            $siguiente_char=ord($cadena[$i])-$salto; 
        }
        
        if ($siguiente_char>127) {
            $siguiente_char=$siguiente_char-95;
        }else if($siguiente_char<33){
            $siguiente_char=$siguiente_char+95;
        }
        $resultado .= chr(intval($siguiente_char));
    }
    return $resultado;
}

?>
</html>

