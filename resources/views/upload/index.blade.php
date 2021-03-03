<x-app-layout>

    <!-- The `multiple` attribute lets users select multiple files. -->
    <input type="file" id="file-selector" multiple>

    <div clas="file-"

    <script>

        const fileSelector = document.getElementById('file-selector');
        fileSelector.addEventListener('change', (event) => {
            const file = event.target.files[0];
           readFile(file);
        });

       function readFile(file) {
           const reader = new FileReader();
           reader.addEventListener('load', (event) => {
               const result = event.target.result;
               // Do something with result
           });

           reader.addEventListener('progress', (event) => {
               if (event.loaded && event.total) {
                   const percent = (event.loaded / event.total) * 100;
                   console.log(`Progress: ${Math.round(percent)}`);
               }
           });
           reader.readAsDataURL(file);
       }
   </script>

</x-app-layout>
