# patterns
some daily php programmingpatterns 

strategypattern:
- change the tools to process data during runtime depending on needed strategy

adapterpattern:
- dont react on changes in webservice directly in your clientcode. Put an adapter inbetween and react there
  so the clientcode will never change, what is good

facadepattern:
- hide complex api calls and logic behind a simply well structured fassade. So you can reduce complexity, increese performance in webservice apis an
  make developers feel lucky to have a fassade.

Decoratorpattern:
- use this pattern if you want to modify an object, message or other constructs dring runtime without to change the method itselfs.
  You can setup a decoratorchain on wich you can modify the modified object again. But keep an eye on th first object. This passtern likes to change the 
  base object! 

Proxypattern:
- use this pattern to reduce serverload on heavy objects like indesignfiles from 1GB Imagedata or longrunning servicerequest
  The baseObjects gets only loaded 1 times. In reallife the initial load is asyncron via messaging

Delegatorpattern

- Use this pattern if you want to centralize your objectcalls to onepoint, so that you dont care about wich specific object
  can handle your request.
 
  The delegator doesnt know anything about the task and who can execute it. He only ask a list with workerclient who can solve the problem.
 
  In this case we have combined the delegatorpattern with a facade, to hide the delegator configuration from the programmer,
  so that he doesnt have to now anything aboutthe workerclients
 
 
  
  
  
