<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Форма для заполнение</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        <!-- Styles -->

        <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.8.0/flowbite.min.css" rel="stylesheet" />
        <link
            rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/tw-elements/dist/css/tw-elements.min.css" />
        <script src="https://cdn.tailwindcss.com/3.3.0"></script>
        <script>
            if(localStorage.getItem("auth_data") == null){
                document.location.href = 'https://www.amocrm.ru/oauth?client_id=f31a47ca-c957-4451-be75-eda7cb121971';
            }else{
                window.token = JSON.parse(localStorage.getItem("auth_data"))["access_token"];
            }
        </script>
        <script src="https://code.jquery.com/jquery-3.7.0.js" integrity="sha256-JlqSTELeR4TLqP0OG9dxM7yDPqX1ox/HfgiSLBj8+kM=" crossorigin="anonymous"></script>
    </head>
    <body >

    <section class="bg-white dark:bg-gray-900">
        <div class="py-6 lg:py-6 px-4 mx-auto max-w-screen-md">
            <h2 class="mb-4 text-4xl tracking-tight font-extrabold text-center text-gray-900 dark:text-white">Форма для заполнение</h2>
            <form  id="contact" action="#" method="POST"  class="space-y-6">
                <div>
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Имя Фамилия</label>
                    <input type="text" name="name" id="name" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 dark:shadow-sm-light" placeholder="name" required>
                </div>

                <div>
                    <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Почта</label>
                    <input type="email" name="email" id="email" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 dark:shadow-sm-light" placeholder="name@flowbite.com" required>
                </div>
                <div>
                    <label for="phone" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Тел.Номер</label>
                    <input  type="text" name="phone" id="phone" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 dark:shadow-sm-light" placeholder="+998 ** ***-**-**" required>
                </div>
                <div>
                    <label for="position" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Дольжность</label>
                    <input type="text" name="position" id="position" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 dark:shadow-sm-light" placeholder="sale manager" required>
                </div>
                <div>
                    <label for="age" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Возраст</label>
                    <input type="number" name="age" id="age" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 dark:shadow-sm-light" placeholder="**" required>
                </div>
                <div>
                    <label for="gender" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Пол</label>
                    <select id="gender" name="gender" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                        <option selected>Выбрать</option>
                        <option value="1327093">Мужской</option>
                        <option value="1327095">Женский</option>
                    </select>
                </div>
                <button type="button" class="py-3 px-5 bg-indigo-700 text-sm font-medium text-center text-white rounded-lg bg-primary-700 sm:w-fit hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800" data-name="contact" onclick="onSubmit(this)">Отправить</button>
            </form>
        </div>
    </section>
    <script>
        function onSubmit(e){
            var frm = $('#'+ $(e).data('name') +'');

            $.ajax({
                type: frm.attr('method'),
                url: '/api/contact',
                headers: {
                    "access_token": token
                },
                data: frm.serialize(),
                success: function (data) {

                    alert(JSON.stringify(data));
                    frm[0].reset();
                },
                error: function (data) {
                    alert(JSON.stringify(data["responseJSON"]));
                },
                callback: function (data){

                }
            });

            return false;
        }
    </script>
    </body>
</html>
