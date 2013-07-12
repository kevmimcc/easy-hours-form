easy-hours-form
===============

A jquery based form for setting hours.

This is not ready for production and I am hoping to get some people to help this be better.

We still need:

1.  Better design for form.  Was probably going to use Bootstrap
2.  Data handling do display the input hours in a nice way
3.  Data handling to get the input hours to format to be used for Local Business Hours on http://schema.org/LocalBusiness

schema.org recommends for Business Hours to be coded like the following:

  <time itemprop="openingHours" datetime="Tu,Th 16:00-20:00">Tuesdays and Thursdays 4-8pm</time>
  
So in the datahandler.php, I am looking for an output in two different forms.  First of all, we need the string that
goes for the datetime property.  
  
  Tu,Th 16:00-20:00
  
Secondly, we need the output as a friendly displayed human format

  Tuesdays and Thursday 4:00pm - 8:00pm
  

Your volunteer work is greatly appreciated amd hopefully we can have a nice easy useable hours form.  I looked around
the internet for a couple hours and didn't find anything I liked, so lets make one.
