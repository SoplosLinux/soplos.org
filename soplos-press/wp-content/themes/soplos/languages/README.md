Instrucciones para traducciones del tema Soplos

- Archivo .pot: `soplos.pot` contiene la plantilla de mensajes exportada.

Cómo generar/actualizar archivos .pot usando `xgettext` o herramientas:

- Con PoEdit: abrir `soplos.pot` y guardar traducciones en `es_ES.po` y compilar a `es_ES.mo`.
- Con WP-CLI (en servidor con WordPress): usar plugins o herramientas de extracción de strings.

Recomendaciones:
- Mantener `Text Domain` como `soplos` en todos los archivos PHP.
- Colocar archivos `.po` y `.mo` en esta carpeta `languages/`.
- Para producir un .pot actualizado, usa PoEdit o gettext tools para escanear el tema.
