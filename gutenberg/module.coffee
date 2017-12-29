class @GutenbergModule
  constructor: (@config) ->
    @create    = wp.element.createElement
    @namespace = @config?.namespace || 'cw/gutenberg-block'
    @title     = @config?.title     || @nameFromNamespace(@namespace)
    @icon      = @config?.icon      || 'universal-access-alt'
    @category  = @config?.category  || 'layout'

    wp.blocks.registerBlockType @namespace,
      title:    @title
      icon:     @icon
      category: @category
      save:     @save
      edit:     @edit

  nameFromNamespace: (namespace) ->
    tmp = namespace.split('/')
    root = tmp[0]
    tmp = tmp.pop().split('-')
    tmp = tmp.map((el)->
      el[0].toUpperCase() + el.substring(1)
    )

    "[#{root}] " + tmp.join(' ')

  save: =>
    @config.save(@) if @config?.save

  edit: =>
    @config.edit(@) if @config?.edit
