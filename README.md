# koods-pipes
Sistema simple de hooks, que funciona como flujos de información

### Ejemplo

```
//Creamos la tubería 
$p = new Pipe('mituberia');
//agregamos información al flujo
$p->add( 'posicion 1<br>' );
$p->add( 'posicion 3<br>' );

//podemos pasar closures al flujo e indicarles su orden
$p->add( function($pos){
    return 'posicion '.$pos.'<br>';
}, 2 );

//agregamos la tuberia al sistema de flujo Pipes
Pipes::add( $p );

//podemos crear tuberias inmediatamente agregandolas al sistema de flujos Pipes, este creará la tubería si no existe
Pipes::add( 'otra Tuberia', function($hello){
    return $hello;
} );

//solo debemos imprimirla y el flujo comenzara a correr, se le pueden pasar parámetros que se recibirán en todos los flujos de la tubería
echo Pipes::flow( 'mituberia', 2 );
echo '<br>';
echo Pipes::flow( 'otra Tuberia', 'hola mundo' );

/* >>>
posicion 1
posicion 2
posicion 3

hola mundo
*/
```
