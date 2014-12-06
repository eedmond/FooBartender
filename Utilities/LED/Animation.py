import LEDGlobals.py

class Animation:
	def __init__(self, data)
		self.data = data
		self.index = 0
		
	def Increment(self)
		self.index++
		if self.index == len(self.data):
			self.index = 0
	
	def GetColor(self)
		return self.data[self.index]