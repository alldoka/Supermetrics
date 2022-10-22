# Part 4/4. Would you use a class/library provided by an external framework in your code, why or why not?

> My goal is to show to my future colleagues how I can look at the bigger picture on architectual level

**Important. The reasoning below is for large products, not a small e-commerce sites**

My short answer to this holly war question - **it depends. But the most common answer - no.**

The idea of using external libraries - to simplify developer's life and save money for the client. But from my experience it works only for the short period of time.

Arguments:
1. Maintenance of such 'Lego application' usually 'eats' all savings
2. We lose the control over the part of the code and add a 'third-party risks' to the project
3. I don't count exceptional cases, like the lack of resources and hot deadlines. For example, we have only one junior/middle developer on the project, who knows this library and rewriting this code will cost a lot, we don't have time for it.

Cases:
1. Big and complex libraries - no. A library can grow up to a useful microservice or even a separate technology. Then it is ok.
2. Middle libraries - could be. In some rare cases, it can really save some time and money. I would think twice before using it.
3. Tiny and simple libraries - no. The most useful libs are usually built into frameworks and programming languages. Then it is a question of choosing the right technology stack for a project. In other cases, I would rather rewrite this piece of code and took the whole responsibility on our side.