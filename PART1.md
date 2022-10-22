# Part 1/4. Read and write some code

>My goal is to show to my future colleagues how I start a new project, write code and tests   

Even though this test assignment is not a real project, I will stick to my normal algorithm of working with a new project.

I always write down my thoughts and doubts. Even if they seem stupid from the first look. You can find them at the end of this document.

I have analyzed:
1. Architecture
2. Code style
3. Coding standards
4. SOLID, OOP, DRY and other
5. Some obvious issues with performance, like the amount and structure of the data
6. Didn't analyze security, risks and so on - not enough information

## Preparation

1. Set goals, priorities, timing
2. Read all documentation and readme (email, github), noted important and questionable
 

## Configure local environment for productive work

1. Analyzed Docker configuration, improved it for my needs
   1. Analyzed PHP and apache configuration, noted possible improvements
2. Configured project in PHPStorm
3. Run `docker-compose up` and checked logs for errors
4. Run `composer install` and checked for upgrades
5. Tested if localhost works
6. Committed my configuration changes

## Code analyzing

1. Analyzed dependencies in composer 
2. Run PHPStorm inspections but didn't apply suggestions. The idea behind is to show to my future colleagues that I use inspection. Less noise in inspection - less chance to commit some dirty code.
3. Started to look into the code deeply. Started with index.php->RouteDispatcher and so on. Make notes for myself about the architecture.
4. Configured debug and started to go step by step, looking how the business logic works on the real data.
5. Get through the code one more time to better understand the coding standard, which the team uses.

## Coding average number of posts per user per month

1. Found the 'natural' way to add my code into existing application  
2. Started to code 'average number' with the dirty way
3. A standard loop. Test if code fails, bug fix, test again 
4. Refactoring, code cleanup, documentation
5. Decide which parts of my code are important for business logic, create unit tests
6. Final testing with all imaginable test cases, found a weird bug
7. After a lot of debugging and manual recalculation, fixed this bug in the 'framework'

## PHPUnit

I have committed just a few unit tests to show that I know how to write tests. On the real project, I would create a common test lib to speed up test development for the whole team. Since it is not necessary now, I will just put my thoughts here.

1. I would suggest creating a universal functional test for http codes (403, 404 and so on) and authorization test
2. Writing functional tests for an average number of posts is redundant
3. Mindful refactoring of unit tests can save a lot of time for developers. DRY must have. But we should be careful with the risk of over complication
4. Test environment should be fully separated
5. For the test data generation, I would recommend PHP enums
6. Tests should be integrated into CI/CD (another big point to discuss)

## Environment
1. We should use a separate .env for docker configuration
2. A lot of suggestions you will see in comments in the docker-compose.yml, Dockerfile, php.ini, apache config
3. Improved 

## Questions to discuss. Or not =)

### Important questions  

1. Is the bug in `ParamsBuilder` known? When resetting date to the start/end of the month, we must reset time to midnight
2. A structure of API response could be simplified. Why do we keep response ID in the body, not in the header? Moving pagination out will remove one level of data. We could take some practices from jsonapi.org.
3. setUnits() method is public, but the value is overridden on the higher level
4. Each request fetches all the data, instead of the data for one month
5. AbstractCalculator's functionality should be split into two parts. It mixes posts filtering and calculation. It is also hard to mock calculators in PHPUnit.

### Code

1. Upgrade code to `8.1`. For example, use `[]` instead of `list`
2. Is sprintf a standard in development? I prefer to use string concatenation for exception messages
3. I prefer to add parameter names functionCall(parameterName: $varible);
4. Why do we use Laravel style routes and controllers arrays in files? We could load this stuff dynamically for the first time and cache until the next release?
5. Why DTO can have nullable date of a post? If the field is required, it should be mapped into not nullable property.
6. String literals with field names like 'from_name' could be moved to a constant. Easier to maintain changes
7. Is it a standard practice for developers to declare(strict_types=1)?

### Architecture and high level

Many questions will appear if we start discussing the architecture. I have stopped thinking about it. Because it is not necessary on the current step. 

1. Who and how will use our application? Third-party applications, android, IOS, bots and so on?
2. Security, performance, scalability, business logic complexity, team level, frequently changing user stories and other requirements to architecture
3. Do we need Swagger documentation for the frontend developers, third-party applications?
4. Are other authentication types required?
5. What about Dependency Injection? Shall we use it in controller factory? Easier to write unit tests, fewer classes.
6. Development processes should be transparent and lightweight
7. Do we have DevOps in the team?