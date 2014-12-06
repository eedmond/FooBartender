
from threading import Lock

import LEDGlobals


class DataBus:
	numLEDs = 88
	animations = [None]*numLEDs
	lock = Lock()
	
	@classmethod
	def GetAllColors(cls):
		cls.lock.acquire()
		colors = []
		for anim in cls.animations:
			if anim == None:
				colors.append(OFF)
			else:
				colors.append(anim.GetColor())
		
		cls.lock.release()
		return colors
	
	@classmethod
	def IncrementAll(cls):
		cls.lock.acquire()
		for anim in cls.animations:
			if anim != None:
				anim.Increment()
		cls.lock.release()
		
	@classmethod
	def SetAnimation(cls, ledIndex, animation):
		cls.lock.acquire()
		cls.animations[ledIndex] = animation
		cls.lock.release()
	
	@classmethod
	def SetAnimationRange(cls, ledIndexArray, animationArray):
		cls.lock.acquire()
		
		for i in xrange(cls.numLEDs):
			cls.animations[ledIndexArray[i]] = animationArray[i]
			
		cls.lock.release()
	

