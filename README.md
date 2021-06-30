Simple application for news parsing.
For start you need to configure database connection in .env file:

* DATABASE_URL="postgresql://news_parser:12345678@127.0.0.1:5434/news_parser?serverVersion=13&charset=utf8"

After that execute 2 commands:

* bin/console doctrine:database:create
* bon/console doctrine:migrations:migrate

Also you need insert initial data into news_parser.parser with 'RbcParser', for this please run command:

* bin/console doctrine:query:sql "INSERT INTO parser (title,description,class,status,created_at,updated_at) VALUES ('RBC parser','Parser from RBC rss feed','RbcParser','active',NOW(),NOW())"

Application provides command for parsing:

* bin/console newsParser

with arguments:
* interval - Interval between parsing iteration in seconds [default: 10]
* cycles-num - Number of cycles to execute, 0 for infinity num [default: 2]

So, if you want run it in 'demonized' mode just choose your favourite way for process running in your OS (like system.d, supervizor, nomad e.t.c) and run command with --cycles-num 0 - it will starts infinity loop! But be aware - if app will catch exception it will be down (I suppose that you favorite process manager can restart the process :)).
