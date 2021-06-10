## How to run project on your machine

1. Take care of dependencies:

`composer install`

2. Start development server

`php -S localhost:8000 -t public`

3. Make post request with json body to `localhost:8000/task`

Sample json, formatted as expected by the API:
```json
{
    "filename":"myscript.sh",
    "tasks":[
        {
            "name":"rm",
            "command":"rm -f /tmp/test",
            "dependencies":[
                "cat"
            ]
        },
        {
            "name":"cat",
            "command":"cat /tmp/test",
            "dependencies":[
                "chown",
                "chmod"
            ]
        },
        {
            "name":"touch",
            "command":"touch /tmp/test"
        },
        {
            "name":"chown",
            "command":"chmod 600 /tmp/test"
        },
        {
            "name":"chmod",
            "command":"chown root:root /tmp/test"
        }
    ]
}
```

## Where to look for the shell scripts

Post request writes a file inside `storage/app` directory of this project
