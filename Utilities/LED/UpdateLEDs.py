# Thread 1
# Updates All LEDs with colors from the shared data bus

import time
from neopixel import *
import LEDGlobals
from DataBus import *
from threading import Thread
from InputParser import RunParser

# LED strip configuration:
LED_COUNT = 88
LED_PIN = 18
LED_FREQ_HZ = 800000
LED_DMA = 5
LED_INVERT = False

# Loops through all LEDs and sets them to corresponding color in "colors"
def SetLEDs(strip, colors):
	for i in range(0, LED_COUNT):
		strip.setPixelColor(i, Color(colors[i][0], colors[i][1], colors[i][2]))
	strip.show()

# Main function
if __name__ == '__main__':
	# create neopixel object
	strip = Adafruit_NeoPixel(LED_COUNT, LED_PIN, LED_FREQ_HZ, LED_DMA, LED_INVERT)
	# initialize library
	strip.begin()

	# boot off the input parsing thread
	parsingThread = Thread(None, RunParser)
	parsingThread.start()
	
	colors = DataBus.GetAllColors()
	while True:
		SetLEDs(strip, colors)
		# possibly leave room for sleep?
		DataBus.IncrementAll()
		colors = DataBus.GetAllColors()
