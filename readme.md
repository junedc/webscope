## Steps to run

<ul>
<li>clone this repository
<pre>
    git clone https://github.com/junedc/webscope.git
</pre>
</li>

<li>Rename .env.example to .env and provide your database details there.</li>

<pre>
    cp .env.example .env
    
    touch database/database.sqlite (optional run only if usinq sqlite)
</pre>

<li>If you're using SQLite run this (Mac Only)</li>

<pre>
    touch database/database.sqlite
</pre>


<li>Run this commands as they shown

   <pre>
   composer install
   npm install
   
   
   
   php artisan key:generate
   php artisan migrate
   php artisan serve
      
   
   </pre> 


</li>




</ul>

