
/* ejercicio 8 */
SELECT DISTINCT M.Nombre, Apellido1, Apellido2 FROM Modelos M, Desfiles X, Diseniadores D 
WHERE M.Cod_Modelo = X.Cod_Modelo AND D.Cod_Diseniador = X.Cod_Diseniador AND Sexo = 'MUJER' AND D.Nombre = 'PEDRO DEL HIERRO' 
AND M.Cod_Modelo IN ( SELECT Cod_Modelo FROM Diseniadores D, Desfiles X WHERE D.Cod_Diseniador = X.Cod_Diseniador AND D.Nombre = 'PURIFICACIÓN GARCÍA'  );

  /* ejercicio 9 */
  SELECT DISTINCT M.Nombre, Apellido1, Apellido2 FROM Modelos M, Desfiles X WHERE M.Cod_Modelo = X.Cod_Modelo AND M.Cod_Modelo NOT IN
   ( SELECT Cod_Modelo FROM Desfiles X, Diseniadores D WHERE D.Cod_Diseniador = X.Cod_Diseniador AND D.Nombre <> 'GIORGIO ARMANI'; );

/* ejercicio 12 */

SELECT Nombre FROM Diseniadores D, Desfiles X WHERE D.Cod_Diseniador = X.Cod_Diseniador AND Fecha = ( SELECT MAX(Fecha) FROM Desfiles    ); 